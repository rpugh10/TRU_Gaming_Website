const searchForm = document.querySelector('#event-search');
const queryInput = document.querySelector('#event-query');
const searchStatus = document.querySelector('#event-search-status');
const results = document.querySelector('#event-results');
const rows = results.querySelector('tbody');

let pendingSearch;
let activeRequest;

async function searchEvents() {
    const request = new AbortController();
    activeRequest = request;
    const query = queryInput.value.trim();
    const params = new URLSearchParams({ url: 'events/search', q: query });
    searchStatus.textContent = 'Searching…';
    results.setAttribute('aria-busy', 'true');

    try {
        const response = await fetch(`index.php?${params}`, { signal: request.signal });
        if (response.status === 401) {
            throw new Error('Sign in again to search events.');
        }
        if (!response.ok) {
            throw new Error('Search is unavailable. Try again.');
        }

        const data = await response.json();
        if (request.signal.aborted) return;

        // The server renders the same escaped row template as the initial list.
        rows.innerHTML = data.rows;
        results.hidden = data.count === 0;
        searchStatus.textContent = data.count === 0
            ? (query ? 'No matching events.' : 'No events yet.')
            : `${data.count} ${data.count === 1 ? 'event' : 'events'} found.`;
    } catch (error) {
        if (!request.signal.aborted) {
            searchStatus.textContent = error instanceof TypeError || error instanceof SyntaxError
                ? 'Search is unavailable. Try again.'
                : error.message;
        }
    } finally {
        if (activeRequest === request) {
            results.removeAttribute('aria-busy');
        }
    }
}

function cancelSearch() {
    clearTimeout(pendingSearch);
    activeRequest?.abort();
}

queryInput.addEventListener('input', () => {
    cancelSearch();
    pendingSearch = setTimeout(searchEvents, 250);
});

searchForm.addEventListener('submit', (event) => {
    event.preventDefault();
    cancelSearch();
    searchEvents();
});
