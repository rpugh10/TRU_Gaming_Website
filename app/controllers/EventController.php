<?php

class EventController
{
    private const FIELDS = [
        'event_name' => ['Event name', 200],
        'game_title' => ['Game title', 200],
        'location' => ['Location', 250],
    ];

    public function index(): void
    {
        $this->requireLogin();
        $canManage = Auth::role() === 'admin';
        $events = (new Event())->getAllEvents();
        $error = $_SESSION['event_error'] ?? null;
        unset($_SESSION['event_error']);
        $token = $this->csrfToken();
        require __DIR__ . '/../views/events.php';
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
        $event = $this->requireEvent(new Event(), $id);
        $this->showForm($id, $event);
    }

    public function update(): void
    {
        $this->requirePostToken();
        $this->save($this->positiveId($_POST['event_id'] ?? null));
    }

    public function delete(): void
    {
        $this->requirePostToken();
        $id = $this->positiveId($_POST['event_id'] ?? null);
        $model = new Event();
        $this->requireEvent($model, $id);

        try {
            if (!$model->deleteEvent($id)) {
                $_SESSION['event_error'] = 'That event no longer exists.';
            }
        } catch (PDOException $exception) {
            if ((int) ($exception->errorInfo[1] ?? 0) !== 1451) {
                throw $exception;
            }
            $_SESSION['event_error'] = 'This event has registrations and cannot be deleted.';
        }

        $this->redirectToList();
    }

    private function save(?int $id): void
    {
        $model = new Event();

        if ($id !== null) {
            $this->requireEvent($model, $id);
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

        foreach (['event_date', 'max_players'] as $field) {
            $value = $_POST[$field] ?? '';
            $data[$field] = is_string($value) ? trim($value) : '';
        }

        $date = $data['event_date'];
        if (!preg_match('/\A[0-9]{4}-[0-9]{2}-[0-9]{2}\z/', $date) ||
            (int) substr($date, 0, 4) < 1000 ||
            !checkdate((int) substr($date, 5, 2), (int) substr($date, 8, 2), (int) substr($date, 0, 4))) {
            $errors[] = 'Enter a valid event date.';
        }

        if (filter_var($data['max_players'], FILTER_VALIDATE_INT, [
            'options' => ['min_range' => 1, 'max_range' => 2147483647],
        ]) === false) {
            $errors[] = 'Enter a positive whole number for the player limit (up to 2147483647).';
        }

        if ($errors !== []) {
            $this->showForm($id, $data, $errors, 422);
            return;
        }

        $saved = $id === null ? $model->createEvent($data) : $model->updateEvent($id, $data);

        if (!$saved) {
            $this->showForm($id, $data, ['Could not save the event.'], 500);
            return;
        }

        $this->redirectToList();
    }

    private function showForm(?int $id, array $data, array $errors = [], int $status = 200): void
    {
        http_response_code($status);
        $fields = self::FIELDS;
        $token = $this->csrfToken();
        require __DIR__ . '/../views/event_form.php';
    }

    private function requireEvent(Event $model, int $id): array
    {
        $event = $model->getEventById($id);
        if ($event === null) {
            http_response_code(404);
            exit('Event not found.');
        }
        return $event;
    }

    private function positiveId(mixed $value): int
    {
        $id = filter_var($value, FILTER_VALIDATE_INT, ['options' => ['min_range' => 1]]);
        if ($id === false) {
            http_response_code(400);
            exit('A valid event ID is required.');
        }
        return $id;
    }

    private function requireLogin(): void
    {
        if (!Auth::check()) {
            header('Location: index.php?url=login');
            exit;
        }
    }

    private function requireAdmin(): void
    {
        $this->requireLogin();
        if (Auth::role() !== 'admin') {
            http_response_code(403);
            exit('Only administrators can manage events.');
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
        header('Location: index.php?url=events', true, 303);
        exit;
    }
}
