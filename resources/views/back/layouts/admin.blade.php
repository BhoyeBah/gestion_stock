<!DOCTYPE html>
<html lang="en">

<head>

    @include('back.partials.head')


    @stack('styles')

</head>

<body id="page-top">

    <!-- Page Wrapper -->
    <div id="wrapper">

        <!-- Sidebar -->
        @include('back.partials.sidebar')
        <!-- End of Sidebar -->

        <!-- Content Wrapper -->
        <div id="content-wrapper" class="d-flex flex-column">

            <!-- Main Content -->
            <div id="content">

                <!-- Topbar -->
                @include('back.partials.topbar')
                <!-- End of Topbar -->

                <!-- Begin Page Content -->
                <div class="container-fluid">

                    {{-- Messages flash de succès / erreur --}}
                    @include('back.partials.alerts')

                    @yield('content')


                </div>
                <!-- /.container-fluid -->

            </div>
            <!-- End of Main Content -->

            <!-- Footer -->
            @include('back.partials.footer')
            <!-- End of Footer -->

        </div>
        <!-- End of Content Wrapper -->

    </div>
    <!-- End of Page Wrapper -->

    <!-- Scroll to Top Button-->
    <a class="scroll-to-top rounded" href="#page-top">
        <i class="fas fa-angle-up"></i>
    </a>

    <!-- Logout Modal-->
    <div class="modal fade" id="logoutModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Ready to Leave?</h5>
                    <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body">Select "Logout" below if you are ready to end your current session.</div>
                <div class="modal-footer">
                    <button class="btn btn-secondary" type="button" data-dismiss="modal">Cancel</button>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <input type="submit" class="btn btn-primary" value="Logout">
                    </form>
                </div>
            </div>
        </div>
    </div>

    @include('back.partials.js')

    @stack('scripts')

    {{-- Styles spécifiques au search (peuvent aller dans ton CSS global) --}}
    <style>
        /* dropdown results */
        #search-wrap {
            position: relative;
            max-width: 420px;
        }

        #search-results {
            position: absolute;
            top: calc(100% + .25rem);
            left: 0;
            right: 0;
            z-index: 1050;
            background: #fff;
            border: 1px solid rgba(0, 0, 0, .125);
            border-radius: .25rem;
            max-height: 300px;
            overflow: auto;
            box-shadow: 0 .5rem 1rem rgba(0, 0, 0, .15);
            display: none;
            /* hidden by default */
        }

        #search-results.show {
            display: block;
        }

        #search-results li {
            padding: .5rem .75rem;
            cursor: pointer;
        }

        #search-results li:hover,
        #search-results li.active {
            background: #f8f9fa;
        }

        #search-results .subtitle {
            font-size: .85rem;
            color: #6c757d;
        }
    </style>

    {{-- Fuse.js --}}
    <script src="https://unpkg.com/fuse.js@6.6.2/dist/fuse.min.js"></script>

    <script type="module">
        // IMPORT: assure-toi que public/assets/js/search-index.js existe et exporte `export const SEARCH_INDEX = [ ... ];`
        import {
            SEARCH_INDEX
        } from "{{ asset('assets/js/search-index.js') }}";

        const input = document.getElementById('search-input');
        const resultsEl = document.getElementById('search-results');

        if (input && resultsEl) {

            const fuse = new Fuse(SEARCH_INDEX, {
                keys: ['title', 'name', 'tags', 'uri_local'],
                threshold: 0.35,
                minMatchCharLength: 2
            });

            let activeIndex = -1;
            let debounceTimer = null;

            function normalize(str) {
                return (str || '').toLowerCase();
            }

            function escapeHtml(unsafe) {
                return String(unsafe || '').replace(/[&<>"'`=\/]/g, function(s) {
                return ({
                    '&': '&amp;',
                    '<': '&lt;',
                    '>': '&gt;',
                    '"': '&quot;',
                    "'": '&#39;',
                    '/': '&#x2F;',
                    '`': '&#x60;',
                        '=': '&#x3D;'
                    })[s];
                });
            }

            function renderResults(list) {
                if (!list || list.length === 0) {
                    resultsEl.classList.remove('show');
                    resultsEl.innerHTML = '';
                    activeIndex = -1;
                    return;
                }
                const html = list.map((r, idx) => {
                    const it = r.item || r; // support Fuse results or raw entries
                    const title = it.title || it.name;
                    const subtitle = (it.tags || []).slice(0, 3).join(' · ');
                    return `<li data-idx="${idx}" data-uri="${escapeHtml(it.uri_local || it.uri)}" class="${idx === activeIndex ? 'active' : ''}">
                  <div class="font-weight-medium">${escapeHtml(title)}</div>
                  <div class="subtitle">${escapeHtml(subtitle)}</div>
                </li>`;
                }).join('');
                resultsEl.innerHTML = html;
                resultsEl.classList.add('show');
            }

            function doSearch(q) {
                q = normalize(q);
                if (!q || q.length < 1) {
                    renderResults([]);
                    return;
                }
                const res = fuse.search(q, {
                    limit: 10
                });
                renderResults(res);
            }

            input.addEventListener('input', (e) => {
                clearTimeout(debounceTimer);
                debounceTimer = setTimeout(() => doSearch(e.target.value), 120);
            });

            // keyboard navigation
            input.addEventListener('keydown', (e) => {
                const items = resultsEl.querySelectorAll('li');
                if (!items.length) return;

                if (e.key === 'ArrowDown') {
                    activeIndex = Math.min(activeIndex + 1, items.length - 1);
                    updateActive(items);
                    e.preventDefault();
                } else if (e.key === 'ArrowUp') {
                    activeIndex = Math.max(activeIndex - 1, 0);
                    updateActive(items);
                    e.preventDefault();
                } else if (e.key === 'Enter') {
                    if (items[activeIndex]) {
                        navigateTo(items[activeIndex].dataset.uri);
                    }
                } else if (e.key === 'Escape') {
                    resultsEl.classList.remove('show');
                }
            });

            // click navigation (delegation)
            resultsEl.addEventListener('click', (e) => {
                const li = e.target.closest('li');
                if (!li) return;
                navigateTo(li.dataset.uri);
            });

            // lose focus -> hide results (small delay to allow click)
            input.addEventListener('blur', () => setTimeout(() => resultsEl.classList.remove('show'), 150));

            function updateActive(items) {
                items.forEach(i => i.classList.remove('active'));
                if (items[activeIndex]) {
                    items[activeIndex].classList.add('active');
                    items[activeIndex].scrollIntoView({
                        block: 'nearest'
                    });
                }
            }

            function navigateTo(uri) {
                if (!uri) return;
                // if uri_local is relative, use it directly to stay on same domain
                location.href = uri;
            }

        } else {
            console.warn('Search integration: #search-input or #search-results not found.');
        }
    </script>

</body>

</html>
