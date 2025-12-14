<style>
    .fi-in-repeatable .fi-in-repeatable-item {
        cursor: pointer
    }

    .fi-in-repeatable .fi-in-repeatable-item.selected {
        border-radius: 0.75rem;
        border-width: 0.25px;
        border-color: var(--primary-500);

    }
</style>
<script>
    (function() {
        function initEggRepeatable() {
            document.querySelectorAll('ul.fi-in-repeatable').forEach(function(repeatable) {
                // find the closest container that also has the checkboxes (tab panel)
                var panel = repeatable.closest('[role="tabpanel"]') || repeatable.parentElement;
                var items = repeatable.querySelectorAll('li.fi-in-repeatable-item');
                var inputs = panel ? panel.querySelectorAll('input[type="checkbox"]') : document.querySelectorAll('input[type="checkbox"]');

                items.forEach(function(item, idx) {
                    if (item.__eggBound) return;
                    item.__eggBound = true;

                    // try to read hidden download_url text inside this item
                    var downloadEl = item.querySelector('.egg-download-url');
                    var download = downloadEl ? downloadEl.textContent.trim() : null;

                    item.addEventListener('click', function(e) {
                        // ignore clicks that originate on interactive controls
                        if (e.target.closest('a,button,input,label')) return;

                        // find checkbox by value (download) or fallback to same index
                        var checkbox = null;
                        if (download) {
                            checkbox = Array.from(inputs).find(function(i) {
                                return i.value === download;
                            });
                        }
                        if (!checkbox) checkbox = inputs[idx];
                        if (!checkbox) return;
                        checkbox.checked = !checkbox.checked;
                        checkbox.dispatchEvent(new Event('change', { bubbles: true }));
                        item.classList.toggle('selected', checkbox.checked);
                    });

                    // sync initial state and listen to checkbox changes
                    var checkboxForSync = null;
                    if (download) checkboxForSync = Array.from(inputs).find(function(i) {
                        return i.value === download;
                    });
                    if (!checkboxForSync) checkboxForSync = inputs[idx];
                    if (checkboxForSync) {
                        if (checkboxForSync.checked) item.classList.add('selected');
                        checkboxForSync.addEventListener('change', function() {
                            item.classList.toggle('selected', checkboxForSync.checked);
                        });
                    }
                });
            });
        }

        // delegated handler for select-all and deselect-all buttons (works even if buttons are added later)
        function handleSelectDeselect(btn, select) {
            try {
                var controls = btn.closest('.egg-controls');
                var encoded = controls ? controls.getAttribute('data-values') : null;
                var values = [];
                if (encoded) {
                    try {
                        values = JSON.parse(atob(encoded));
                    } catch (err) {
                        values = [];
                    }
                }

                // find a good panel/context to search for checkboxes
                var panel = btn.closest('[role="tabpanel"]') || btn.closest('.egg-controls')?.parentElement || btn.closest('.fi-in-repeatable')?.closest('[role="tabpanel"]') || document;

                if (values && values.length) {
                    values.forEach(function(v) {
                        var input = Array.from(panel.querySelectorAll('input[type="checkbox"]')).find(function(i) {
                            return i.value === v;
                        });
                        if (input) {
                            if (select && !input.checked) {
                                input.checked = true;
                                input.dispatchEvent(new Event('change', { bubbles: true }));
                            } else if (!select && input.checked) {
                                input.checked = false;
                                input.dispatchEvent(new Event('change', { bubbles: true }));
                            }
                        }
                    });
                } else {
                    // fallback: operate on all checkboxes in the panel
                    var inputs = panel.querySelectorAll('input[type="checkbox"]');
                    inputs.forEach(function(i) {
                        if (select) {
                            if (!i.checked) {
                                i.checked = true;
                                i.dispatchEvent(new Event('change', { bubbles: true }));
                            }
                        } else {
                            if (i.checked) {
                                i.checked = false;
                                i.dispatchEvent(new Event('change', { bubbles: true }));
                            }
                        }
                    });
                }
            } catch (e) {
                console.error(e);
            }
        }

        document.addEventListener('click', function(e) {
            var btn = e.target.closest('.egg-select-all');
            if (btn) {
                e.preventDefault();
                handleSelectDeselect(btn, true);
                return;
            }
            var btn2 = e.target.closest('.egg-deselect-all');
            if (btn2) {
                e.preventDefault();
                handleSelectDeselect(btn2, false);
                return;
            }
        });

        document.addEventListener('DOMContentLoaded', function() {
            initEggRepeatable();
            // also try after short delays in case Filament loads async
            setTimeout(initEggRepeatable, 500);
            setTimeout(initEggRepeatable, 1500);
        });
    })();
</script>
