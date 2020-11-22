(function () {
    window.document.documentElement.classList.add('has-js');

    /**
     * Detects whether a web storage API is both supported and available.
     *
     * @link     https://developer.mozilla.org/en-US/docs/Web/API/Web_Storage_API/Using_the_Web_Storage_API#Feature-detecting_localStorage
     * @constant {boolean} Returns TRUE if the `localStorage` API is supported and available.
     */
    const isStorageAvailable = (function () {
        let storage;

        try {
            storage = window.localStorage;
            const x = '__storage_test__';
            storage.setItem(x, x);
            storage.removeItem(x);
            return true;
        } catch (error) {
            return error instanceof DOMException && (
                // everything except Firefox
                error.code === 22 ||
                // Firefox
                error.code === 1014 ||
                // test name field too, because code might not be present
                // everything except Firefox
                error.name === 'QuotaExceededError' ||
                // Firefox
                error.name === 'NS_ERROR_DOM_QUOTA_REACHED') &&
                // acknowledge QuotaExceededError only if there's something already stored
                (storage && storage.length !== 0);
        }
    })();

    /**
     * An event listener object to toggle a range of checkboxes using the shift-key.
     *
     * @typedef {Object} ToggleCheckedInputRangeEventListener
     *
     * @implements {EventListener}
     *
     * @property {HTMLInputElement} lastInput               - The previously checked, or unchecked, input.
     * @property {HTMLElement}      commonAncestorContainer - The common ancestor that contains the input collection.
     * @property {Function}         querySelectorAll        - The default input collection handler.
     * @property {Function}         handleEvent             - The default event listener.
     */

    /**
     * Creates an event listener object to toggle a range of checkboxes using the shift-key.
     *
     * @param  {Function} [querySelectorAllFn] - A proxy event handler function.
     *
     * @return {ToggleCheckedInputRangeEventListener}
     */
    const createEventListenerToToggleCheckedInputRange = function (querySelectorAllFn) {
        const commonAncestorContainerSelector = [
            'ul',
            'ol',
            'dl',
            'fieldset',
            'form',
            'table',
            'details',
            'aside',
            'footer',
            'header',
            'section',
            'main',
        ].join(',');

        return {
            lastInput: null,
            commonAncestorContainer: null,
            querySelectorAll: querySelectorAllFn || function () {
                this.commonAncestorContainer.querySelectorAll('input[type="checkbox"]');
            },
            handleEvent: function (event) {
                let currInput = event.target;
                if (currInput.type !== 'checkbox') {
                    return;
                }

                if (event.shiftKey && this.lastInput !== null && this.lastInput !== currInput) {
                    let inputs, inBetween, options;

                    if (!this.commonAncestorContainer) {
                        this.commonAncestorContainer = currInput.closest(commonAncestorContainerSelector);
                    }

                    inputs  = this.querySelectorAll();
                    options = {
                        bubbles: true,
                        cancelable: true
                    };

                    this.lastInput.checked = currInput.checked;
                    this.lastInput.dispatchEvent(new Event('change', options));

                    inBetween = false;
                    inputs.forEach((input) => {
                        if (input === this.lastInput || input === currInput) {
                            inBetween = !inBetween;
                        } else if (inBetween) {
                            input.checked = currInput.checked;
                            input.dispatchEvent(new Event('change', options));
                        }
                    });
                }

                this.lastInput = currInput;
            }
        };
    };

    /**
     * An event listener object to persist input checked state in web storage.
     *
     * @typedef {Object} PersistCheckedInputEventListener
     *
     * @implements {EventListener}
     *
     * @property {Storage}  storageArea   - The web storage object.
     * @property {Function} getStoreKey   - The default storage key resolver.
     * @property {Function} getStoreValue - The default storage value resolver.
     * @property {Function} handleEvent   - The default event listener.
     * @property {Function} updateStore   - The web storage object updater.
     */

    /**
     * Creates an event listener object to persist input checked state in web storage.
     *
     * @param  {Storage}  store     - The web storage object.
     * @param  {Function} [keyFn]   - A storage key resolver.
     * @param  {Function} [valFn]   - A storage value resolver.
     * @param  {Function} [proxyFn] - A proxy event handler function.
     *
     * @return {PersistCheckedInputEventListener}
     */
    const createEventListenerToPersistCheckedInput = function (store, keyFn, valFn, proxyFn) {
        return {
            storageArea: store,
            getStoreKey: keyFn || function (input, event) {
                return `${input.name}_${input.value}`;
            },
            getStoreValue: valFn || function (input, event) {
                return 1;
            },
            handleEvent: proxyFn || function (event) {
                this.updateStore(event.target, event);
            },
            updateStore: function (input, event) {
                let key = this.getStoreKey(input, event);

                if (input.checked) {
                    this.storageArea.setItem(key, this.getStoreValue(input, event));
                } else {
                    this.storageArea.removeItem(key);
                }
            }
        };
    }

    /**
     * Catalogue Tracker
     */
    const Track = (function () {
        const storePrefix = 'ds_order_';
        const checklistRangeQueryCallback = function () {
            let selector = 'input[type="checkbox"]';

            if (this.commonAncestorContainer.classList.contains('is-searching')) {
                selector = 'tr.is-match ' + selector;
            }

            return this.commonAncestorContainer.querySelectorAll(selector);
        };
        const persistCheckedInputEventCallback = function (event) {
            let input = event.target;
            this.updateStore(input, event);

            let row = input.closest('tr');
            if (row) {
                row.classList.toggle('tr-todo', !input.checked);
                row.classList.toggle('tr-done', input.checked);
            }
        };
        const persistCheckedInputEventArgs = [
            (input) => storePrefix + input.value,
            () => new Date(),
            persistCheckedInputEventCallback
        ];

        return {
            /**
             * Initialize the given tables.
             *
             * @param  {HTMLTableElement[]} tables - One or more table elements.
             * @param  {Storage}            store  - A web storage object.
             * @return {void}
             */
            init: function (tables, store) {
                if (tables instanceof Element) {
                    tables = [ tables ];
                }

                for (const table of tables) {
                    for (const tbody of table.tBodies) {
                        for (const row of tbody.rows) {
                            try {
                                row.input = document.getElementById('cb-select-' + row.dataset.orderId);
                                if (row.input) {
                                    row.input.checked = store.getItem(storePrefix + row.input.value);

                                    row.classList.toggle('tr-todo', !row.input.checked);
                                    row.classList.toggle('tr-done', row.input.checked);
                                }
                            } catch (error) {
                                console.error('[Bridges]', '[Table Body Row: ' + row.rowIndex + ']', error);
                            }
                        }
                    }

                    table.eventset = table.eventset || {};

                    table.eventset.click = createEventListenerToToggleCheckedInputRange(checklistRangeQueryCallback);
                    table.addEventListener('click', table.eventset.click, { passive: true });

                    table.eventset.change = createEventListenerToPersistCheckedInput(...[ store, ...persistCheckedInputEventArgs ]);
                    table.addEventListener('change', table.eventset.change, { passive: true });

                    table.classList.add('is-trackable');
                }
            },
            /**
             * Uninitialize the given tables.
             *
             * @param  {HTMLTableElement[]} tables - One or more table elements.
             * @return {void}
             */
            unload: function (tables) {
                if (tables instanceof Element) {
                    tables = [ tables ];
                }

                for (const table of tables) {
                    if (table.eventset.click) {
                        table.removeEventListener('click', table.eventset.click);
                    }

                    if (table.eventset.change) {
                        table.removeEventListener('change', table.eventset.change);
                    }

                    table.classList.remove('is-trackable');
                }
            }
        };
    })();

    /**
     * Catalogue Searcher
     *
     * @link https://github.com/codemirror/CodeMirror/blob/v3.12/addon/search/search.js CodeMirror Add-On
     */
    const Search = (function () {
        const elem = {
            tables: null,
        }
        const state = {
            lastQuery: null,
            queryText: null,
            query:     null,
            results:   [],
        }

        /**
         * Parses the escape sequences in the string.
         *
         * @param  {string} string - The search query to parse.
         * @return {string} The filtered input.
         */
        function parseString(string) {
            return string.replace(/\\([nrt\\])/g, function (match, ch) {
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
         * Parses the the string into a regular expression object.
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
         * Finalizes the query for searching.
         *
         * @param  {(string|RegExp)} query - The parsed search query.
         * @return {RegExp} The regular expression object.
         */
        function prepareQuery(query) {
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
         * Prepares the search state and parses the query.
         *
         * @param  {string} query - The input query.
         * @return {(string|RegExp)} The parsed query.
         */
        function prepareSearch(query) {
            state.queryText = query;
            state.query     = parseQuery(query);
            state.results   = [];

            return state.query;
        }

        /**
         * Clears the search.
         *
         * @return {void}
         */
        function clearSearch() {
            state.lastQuery = state.query;
            state.query     = null;
            state.queryText = null;
            state.results   = [];
        }

        return {
            /**
             * Initialize the given form and tables.
             *
             * @param  {HTMLTableElement[]} tables - One or more table elements.
             * @param  {HTMLFormElement}    form   - A form element.
             * @return {void}
             */
            init: function (tables, form) {
                if (tables instanceof Element) {
                    tables = [ tables ];
                }

                elem.tables = tables;

                form.eventset = form.eventset || {};

                form.eventset.submit = (event) => {
                    event.preventDefault();

                    const input = event.target.querySelector('[name="query"]');
                    const reset = event.target.querySelector('[type="reset"]');
                    if (input && input.value.length) {
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
                form.addEventListener('submit', form.eventset.submit, {
                    capture: true,
                });

                form.eventset.reset = (event) => {
                    const reset = event.target.querySelector('[type="reset"]');
                    if (reset) {
                        reset.hidden = true;
                    }
                    this.clear();
                };
                form.addEventListener('reset', form.eventset.reset, {
                    capture: true,
                    passive: true,
                });

                form.classList.add('is-searchable');
            },
            /**
             * Uninitialize the given form and tables.
             *
             * @param  {HTMLFormElement}    form   - A form element.
             * @param  {HTMLTableElement[]} tables - One or more table elements.
             * @return {void}
             */
            unload: function (form, tables) {
                if (form.eventset.submit) {
                    form.removeEventListener('submit', form.eventset.submit);
                }

                if (form.eventset.reset) {
                    form.removeEventListener('reset', form.eventset.reset);
                }

                this.clear();
            },
            /**
             * Searches the dataset for occurrences of the query.
             *
             * @param  {string} query - The input query.
             * @return {array<HTMLTableRowElement>} Zero or more rows.
             */
            find: function (query) {
                clearSearch();

                query = prepareQuery(prepareSearch(query));

                const TIME_START = new Date();

                elem.tables.forEach(function (table) {
                    for (const tbody of table.tBodies) {
                        for (const tbr of tbody.rows) {
                            query.lastIndex = 0;
                            if (query.test(tbr.innerText)) {
                                state.results.push(tbr);
                            }
                        }
                    }
                });

                console.log('Searched (' + ((new Date() - TIME_START) / 1000) + '):', state.results);
            },
            /**
             * Filters the dataset for occurrences of the query.
             *
             * @param  {string} query - The input query.
             * @return {array<HTMLTableRowElement>} Zero or more rows.
             */
            filter: function (query) {
                clearSearch();

                query = prepareQuery(prepareSearch(query));
                window.foo = query;

                const TIME_START = new Date();

                elem.tables.forEach(function (table) {
                    table.classList.add('is-searching');
                    for (const tbody of table.tBodies) {
                        for (const tbr of tbody.rows) {
                            query.lastIndex = 0;
                            if (query.test(tbr.innerText)) {
                                tbr.classList.add('is-match');
                                state.results.push(tbr);
                            } else {
                                tbr.classList.remove('is-match');
                            }
                        }
                    }
                });

                console.log('Filtered (' + ((new Date() - TIME_START) / 1000) + '):', state.results);
            },
            clear: function () {
                clearSearch();

                elem.tables.forEach(function (table) {
                    table.classList.remove('is-searching');

                    table.querySelectorAll('tbody tr.is-match').forEach(function (tbr) {
                        tbr.classList.remove('is-match');
                    });
                });

                console.log('Cleared search');
            }
        };
    })();

    window.Bridges = {
        find:        Search.find,
        filter:      Search.filter,
        clearFilter: Search.clear,
    };

    window.addEventListener('load', function () {
        const form   = window.document.getElementById('ds-search');
        const tables = window.document.querySelectorAll('table.ds-orders');

        try {
            if (isStorageAvailable) {
                Track.init(tables, window.localStorage);
                Search.init(tables, form);
            }
        } catch (error) {
            console.error('[Bridges]', error);
        }
    });
})();
