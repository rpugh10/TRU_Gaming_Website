<?php

class MemberController
{
    private const FIELDS = [
        'real_name' => ['Real name', 100],
        'gamertag' => ['Gamertag', 150],
        'email' => ['Email', 150],
        'program' => ['Program', 100],
        'favorite_game' => ['Favorite game', 150],
        'gaming_platform' => ['Gaming platform', 100],
    ];

    public function index(): void
    {
        $this->requireAdmin();
        $members = (new Member())->all();
        $error = $_SESSION['member_error'] ?? null;
        unset($_SESSION['member_error']);
        $token = $this->csrfToken();
        require __DIR__ . '/../views/members.php';
    }

    public function showCreate(): void
    {
        $this->requireAdmin();
        $this->showForm(null, []);
    }

    public function create(): void
    {
        $this->requirePostToken();
        $this->save(null);
    }

    public function showEdit(): void
    {
        $this->requireAdmin();
        $id = $this->positiveId($_GET['id'] ?? null);
        $member = $this->requireMember(new Member(), $id);
        $this->showForm($id, $member);
    }

    public function update(): void
    {
        $this->requirePostToken();
        $this->save($this->positiveId($_POST['member_id'] ?? null));
    }

    public function delete(): void
    {
        $this->requirePostToken();
        $id = $this->positiveId($_POST['member_id'] ?? null);
        $model = new Member();
        $this->requireMember($model, $id);

        try {
            if (!$model->delete($id)) {
                $_SESSION['member_error'] = 'That member no longer exists.';
            }
        } catch (PDOException $exception) {
            if ((int) ($exception->errorInfo[1] ?? 0) !== 1451) {
                throw $exception;
            }
            $_SESSION['member_error'] = 'This member has event registrations and cannot be deleted.';
        }

        $this->redirectToList();
    }

    private function save(?int $id): void
    {
        $model = new Member();

        if ($id !== null) {
            $this->requireMember($model, $id);
        }

        $data = [];
        $errors = [];
        foreach (self::FIELDS as $field => [$label, $limit]) {
            $value = $_POST[$field] ?? '';
            $data[$field] = is_string($value) ? trim($value) : '';
            if ($data[$field] === '' || mb_strlen($data[$field], 'UTF-8') > $limit) {
                $errors[] = "$label is required and must be at most $limit characters.";
            }
        }

        if ($data['email'] !== '' && !filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
            $errors[] = 'Enter a valid email address.';
        }

        $password = null;
        if ($id === null) {
            $value = $_POST['user_id'] ?? '';
            $data['user_id'] = is_string($value) ? trim($value) : '';
            $username = $_POST['username'] ?? '';
            $data['username'] = is_string($username) ? trim($username) : '';
            $submittedPassword = $_POST['password'] ?? '';
            $confirmation = $_POST['password_confirmation'] ?? '';

            if ($data['user_id'] === '') {
                if ($data['username'] === '' || mb_strlen($data['username'], 'UTF-8') > 50) {
                    $errors[] = 'Enter a username of up to 50 characters.';
                }
                if (!is_string($submittedPassword) || mb_strlen($submittedPassword, 'UTF-8') < 12 ||
                    strlen($submittedPassword) > 72 || str_contains($submittedPassword, "\0")) {
                    $errors[] = 'Enter a password with at least 12 characters and at most 72 bytes.';
                } elseif ($submittedPassword !== $confirmation) {
                    $errors[] = 'The passwords must match.';
                } else {
                    $password = $submittedPassword;
                }
            } elseif (filter_var($data['user_id'], FILTER_VALIDATE_INT, ['options' => ['min_range' => 1]]) === false) {
                $errors[] = 'Enter the ID of an existing user account.';
            } elseif ($data['username'] !== '' || $submittedPassword !== '' || $confirmation !== '') {
                $errors[] = 'Choose either a new login or an existing user ID.';
            }
        }

        if ($errors !== []) {
            $this->showForm($id, $data, $errors, 422);
            return;
        }

        try {
            $saved = match (true) {
                $id !== null => $model->update($id, $data),
                $password !== null => $this->createWithAccount($model, $data, $password),
                default => $model->create($data),
            };
        } catch (PDOException $exception) {
            $message = match ((int) ($exception->errorInfo[1] ?? 0)) {
                1062 => $password !== null
                    ? 'That username, gamertag, or email address is already in use.'
                    : 'That gamertag or email address is already in use.',
                1452 => 'The selected user account does not exist.',
                default => throw $exception,
            };
            $this->showForm($id, $data, [$message], 422);
            return;
        }

        if (!$saved) {
            $this->showForm($id, $data, ['Could not save the member.'], 500);
            return;
        }

        $this->redirectToList();
    }

    private function showForm(?int $id, array $data, array $errors = [], int $status = 200): void
    {
        http_response_code($status);
        $fields = self::FIELDS;
        $token = $this->csrfToken();
        require __DIR__ . '/../views/member_form.php';
    }

    private function createWithAccount(Member $model, array $data, string $password): bool
    {
        $db = Database::getInstance();
        $db->beginTransaction();

        try {
            $data['user_id'] = (new User())->createMemberAccount($data['username'], $password);
            if (!$model->create($data)) {
                return false;
            }
            return $db->commit();
        } finally {
            if ($db->inTransaction()) {
                $db->rollBack();
            }
        }
    }

    private function requireMember(Member $model, int $id): array
    {
        $member = $model->getById($id);
        if ($member === null) {
            http_response_code(404);
            exit('Member not found.');
        }
        return $member;
    }

    private function positiveId(mixed $value): int
    {
        $id = filter_var($value, FILTER_VALIDATE_INT, ['options' => ['min_range' => 1]]);
        if ($id === false) {
            http_response_code(400);
            exit('A valid member ID is required.');
        }
        return $id;
    }

    private function requireAdmin(): void
    {
        if (!Auth::check()) {
            header('Location: index.php?url=login');
            exit;
        }
        if (Auth::role() !== 'admin') {
            http_response_code(403);
            exit('Only administrators can manage members.');
        }
    }

    private function requirePostToken(): void
    {
        $this->requireAdmin();
        $expected = $_SESSION['csrf_token'] ?? '';
        $submitted = $_POST['csrf_token'] ?? null;
        if ($expected === '' || !is_string($submitted) || !hash_equals($expected, $submitted)) {
            http_response_code(403);
            exit('Invalid CSRF token.');
        }
    }

    private function csrfToken(): string
    {
        return $_SESSION['csrf_token'] ??= bin2hex(random_bytes(32));
    }

    private function redirectToList(): void
    {
        header('Location: index.php?url=members', true, 303);
        exit;
    }
}
