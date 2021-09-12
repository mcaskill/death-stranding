/**
 * @file Orders Finder
 */

/**
 * Finalizes the query for searching.
 *
 * If the search query is not already a {RegExp} instance, make it so,
 * escaping any special characters.
 *
 * @param  {(string|RegExp)} query - The parsed search query.
 * @return {RegExp} The regular expression object.
 */
function finalizeQuery(query) {
    if (typeof query === 'string') {
        query = new RegExp(
            query.replace(/[\-\[\]\/\{\}\(\)\*\+\?\.\\\^\$\|]/g, "\\$&"),
            (isQueryCaseInsensitive(query) ? 'gi' : 'g')
        );
    } else if (!query.global) {
        query = new RegExp(
            query.source,
            (query.ignoreCase ? 'gi' : 'g')
        );
    }

    return query;
}

/**
 * Determines if the query is case-insensitive.
 *
 * A search query is condisered case-insensitive
 * if it is a string of all lowercase characters.
 *
 * @param  {(string|RegExp)} query The input to test.
 * @return {boolean}
 */
function isQueryCaseInsensitive(query) {
    return (typeof query === 'string' && query === query.toLowerCase());
}

/**
 * Determines if the regular expression is anchored
 * to the start or end of the pattern.
 *
 * @param  {RegExp} query The regular expression to test.
 * @return {boolean}
 */
function isRegExpPatternAnchored(query) {
    return query.source.match(/^\^|\$$/);
}

/**
 * Parses the the string into a regular expression object.
 *
 * If the search query is a regular expression pattern,
 * convert it to {RegExp} instance.
 *
 * If the search query is a plain query, fix any escaped
 * special characters.
 *
 * @param  {string} string - The search query to parse.
 * @return {(string|RegExp)} The query string or regular expression object.
 */
function parseQuery(query) {
    const isRE = query.match(/^\/(.*)\/([a-z]*)$/);
    if (isRE) {
        try {
            query = new RegExp(isRE[1], isRE[2].indexOf('i') === -1 ? '' : 'i');
        }
        catch (error) {
            // Not a regular expression after all, do a string search
            console.error('[Bridges]', '[Parse Query: ' + query + ']', error);
        }
    } else {
        query = parseString(query);
    }

    if (typeof query === 'string' ? query === '' : query.test('')) {
        query = /x^/;
    }

    return query;
}

/**
 * Parses the escape sequences in the string.
 *
 * Fix any escaped special characters.
 *
 * @param  {string} string - The search query to parse.
 * @return {string} The filtered input.
 */
function parseString(string) {
    return string.replace(/\\([nrt\\])/g, (match, ch) => {
        if (ch === 'n') {
            return "\n";
        }

        if (ch === 'r') {
            return "\r";
        }

        if (ch === 't') {
            return "\t";
        }

        if (ch === "\\") {
            return "\\";
        }

        return match;
    });
}

/**
 * Formats the milliseconds to seconds.
 *
 * @param  {number} time - The milliseconds to format.
 * @return {string} The formatted time in seconds.
 */
function toSeconds(time) {
    return (time / 1000) + 's';
}

/**
 * Orders Finder
 *
 * @link https://github.com/codemirror/CodeMirror/blob/v3.12/addon/search/search.js CodeMirror Add-On
 */
export default class Search
{
    /**
     * @property {HTMLTableElement[]} - One or more table elements.
     */
    tables;

    /**
     * @property {HTMLFormElement} - A form element.
     */
    form;

    /**
     * @property {string} - The previous search query.
     */
    lastQuery;

    /**
     * @property {string} - The raw search query.
     */
    queryText;

    /**
     * @property {string} - The parsed search query.
     */
    query;

    /**
     * @property {HTMLTableRowElement[]} - The table row elements
     *     that match the search query.
     */
    results = [];

    /**
     * @property {HTMLElement} - A status element.
     */
    status;

    /**
     * Creates a new Finder.
     *
     * @param {HTMLTableElement[]} tables   - One or more table elements.
     * @param {HTMLFormElement}    form     - A form element.
     * @param {HTMLElement}        [status] - A status element.
     */
    constructor(tables, form, status)
    {
        if (tables instanceof Element) {
            tables = [ tables ];
        }

        this.tables = tables;
        this.form   = form;
        this.status = status;

        this._registerEventListeners();

        this.form.classList.add('is-searchable');
    }

    /**
     * Uninitialize the searcher.
     *
     * @return {void}
     */
    destroy() {
        this._removeEventListeners();

        this.clear();

        this.form.classList.remove('is-searchable');
    }

    /**
     * Searches the dataset for occurrences of the query.
     *
     * @param  {string} query - The input query.
     * @return {HTMLTableRowElement[]} Zero or more rows.
     */
    find(query) {
        this._clearState();

        query = finalizeQuery(parseQuery(query));

        console.log('[Bridges]', 'Query:', query);

        const results = [];

        const TIME_START = new Date();

        const isQueryAnchored = isRegExpPatternAnchored(query);

        this.tables.forEach((table) => {
            for (const tbody of table.tBodies) {
                for (const tr of tbody.rows) {
                    if (isQueryAnchored) {
                        for (const tc of tr.cells) {
                            query.lastIndex = 0;
                            if (query.test(tc.innerText)) {
                                results.push(tr);
                                break;
                            }
                        }
                    } else {
                        query.lastIndex = 0;
                        if (query.test(tr.innerText)) {
                            results.push(tr);
                        }
                    }
                }
            }
        });

        this.log(`${results.length} results`, false);

        console.log('[Bridges]', 'Searched (' + toSeconds(new Date() - TIME_START) + '):', results);

        return results;
    }

    /**
     * Filters the dataset for occurrences of the query.
     *
     * @param  {string} query - The input query.
     * @return {HTMLTableRowElement[]} Zero or more rows.
     */
    filter(query) {
        this._clearState();

        query = finalizeQuery(this._prepareSearch(query));

        console.log('[Bridges]', 'Query:', query);

        const TIME_START = new Date();

        const isQueryAnchored = isRegExpPatternAnchored(query);

        this.tables.forEach((table) => {
            table.classList.add('is-searching');
            for (const tbody of table.tBodies) {
                for (const tr of tbody.rows) {
                    if (isQueryAnchored) {
                        for (const tc of tr.cells) {
                            query.lastIndex = 0;
                            if (query.test(tc.innerText)) {
                                tr.classList.add('is-match');
                                this.results.push(tr);
                                break;
                            } else {
                                tr.classList.remove('is-match');
                            }
                        }
                    } else {
                        query.lastIndex = 0;
                        if (query.test(tr.innerText)) {
                            tr.classList.add('is-match');
                            this.results.push(tr);
                        } else {
                            tr.classList.remove('is-match');
                        }
                    }
                }
            }
        });

        this.log(`${this.results.length} results`);

        console.log('[Bridges]', 'Filtered (' + toSeconds(new Date() - TIME_START) + '):', this.results);

        return this.results;
    }

    clear() {
        this._clearState();

        this.tables.forEach((table) => {
            table.classList.remove('is-searching');

            table.querySelectorAll('tbody tr.is-match').forEach((tr) => {
                tr.classList.remove('is-match');
            });
        });

        if (this.status) {
            this.status.innerHTML = '';
        }

        console.log('[Bridges]', 'Cleared search');
    }

    /**
     * @param  {string}  message - The message to log.
     * @param  {boolean} [live]  - Whether to output message.
     * @return {void}
     */
    log(message, live) {
        console.info('[Bridges]', 'Status:', message);

        if (live === false) {
            return;
        }

        if (this.status) {
            this.status.innerHTML = message;
            /*
            setTimeout(() => {
                this.status.innerHTML = '';
            }, 1000);
            */
        }
    }

    /**
     * Clears the search state.
     *
     * @return {void}
     */
    _clearState() {
        this.lastQuery = this.query;
        this.query     = null;
        this.queryText = null;
        this.results   = [];
    }

    /**
     * Prepares the search state and parses the query.
     *
     * @param  {string} query - The input query.
     * @return {(string|RegExp)} The parsed query.
     */
    _prepareSearch(query) {
        this.queryText = query;
        this.query     = parseQuery(query);
        this.results   = [];

        return this.query;
    }

    /**
     * Registers event listeners.
     *
     * @return {void}
     */
    _registerEventListeners() {
        this.form.eventset = this.form.eventset || {};

        this.form.eventset.submit = (event) => {
            event.preventDefault();

            const input = event.target.querySelector('[name="query"]');
            const reset = event.target.querySelector('[type="reset"]');

            if (!input) {
                console.error('[Bridges]', 'Missing search query input');
                return;
            }

            if (input.value.length) {
                if (reset) {
                    reset.hidden = false;
                }
                Promise.resolve(this.filter(input.value));
            } else {
                if (reset) {
                    reset.hidden = true;
                }
                Promise.resolve(this.clear());
            }
        };

        this.form.addEventListener('submit', this.form.eventset.submit, {
            capture: true,
            passive: false,
        });

        this.form.eventset.reset = (event) => {
            const input = event.target.querySelector('[name="query"]');
            const reset = event.target.querySelector('[type="reset"]');

            if (input) {
                input.focus();
            }

            if (reset) {
                reset.hidden = true;
            }

            this.clear();
        };

        this.form.addEventListener('reset', this.form.eventset.reset, {
            capture: true,
            passive: true,
        });
    }

    /**
     * Removes event listeners.
     *
     * @return {void}
     */
    _removeEventListeners() {
        if (this.form.eventset.submit) {
            this.form.removeEventListener('submit', this.form.eventset.submit);
        }

        if (this.form.eventset.reset) {
            this.form.removeEventListener('reset', this.form.eventset.reset);
        }
    }
}
