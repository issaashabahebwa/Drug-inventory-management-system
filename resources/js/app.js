// ============================================================
// Kiswa Health Centre III — Drug Inventory System
// Global JS (Bootstrap 5 + custom utilities)
// ============================================================

// Bootstrap is loaded via CDN in the layout — no import needed here.

// ── Theme: restore saved preference before first paint ───────
(function () {
    const saved = localStorage.getItem('kiswa-theme');
    if (saved === 'dark') {
        document.documentElement.setAttribute('data-theme', 'dark');
    }
})();

document.addEventListener('DOMContentLoaded', function () {

    // ── Sidebar toggle (mobile) ──────────────────────────────────
    const sidebarToggle = document.getElementById('sidebarToggle');
    const sidebar       = document.getElementById('sidebar');
    const overlay       = document.getElementById('sidebarOverlay');

    if (sidebarToggle && sidebar) {
        sidebarToggle.addEventListener('click', function () {
            sidebar.classList.toggle('show');
            overlay && overlay.classList.toggle('show');
        });
        overlay && overlay.addEventListener('click', function () {
            sidebar.classList.remove('show');
            overlay.classList.remove('show');
        });
    }

    // ── Auto-dismiss flash alerts after 5 s ─────────────────────
    document.querySelectorAll('.alert-auto-dismiss').forEach(function (el) {
        setTimeout(function () {
            const bsAlert = bootstrap.Alert.getOrCreateInstance(el);
            bsAlert && bsAlert.close();
        }, 5000);
    });

    // ── Confirm dangerous actions ────────────────────────────────
    document.querySelectorAll('[data-confirm]').forEach(function (el) {
        el.addEventListener('click', function (e) {
            const msg = el.getAttribute('data-confirm') || 'Are you sure?';
            if (!confirm(msg)) {
                e.preventDefault();
                e.stopPropagation();
            }
        });
    });

    // ── Drug → Batch AJAX loader (dispensing form) ───────────────
    const drugSelect  = document.getElementById('drug_id');
    const batchSelect = document.getElementById('stock_batch_id');
    const batchInfo   = document.getElementById('batchInfo');
    const qtyInput    = document.getElementById('quantity_dispensed');

    if (drugSelect && batchSelect) {
        drugSelect.addEventListener('change', function () {
            const drugId = this.value;
            if (!drugId) {
                batchSelect.innerHTML = '<option value="">— select drug first —</option>';
                clearBatchInfo();
                return;
            }

            fetch(`/api/drugs/${drugId}/batches`)
                .then(r => r.json())
                .then(batches => {
                    batchSelect.innerHTML = '<option value="">— select batch (FEFO) —</option>';
                    if (batches.length === 0) {
                        batchSelect.innerHTML += '<option disabled>No active batches available</option>';
                        return;
                    }
                    batches.forEach(function (b) {
                        const opt = document.createElement('option');
                        opt.value = b.id;
                        opt.dataset.qty     = b.quantity_remaining;
                        opt.dataset.expiry  = b.expiry_date;
                        opt.dataset.color   = b.expiry_color;
                        opt.dataset.days    = b.days_to_expiry;
                        opt.textContent = `${b.batch_number}  |  Exp: ${b.expiry_date}  |  Qty: ${b.quantity_remaining}`;
                        if (batches.indexOf(b) === 0) opt.selected = true;
                        batchSelect.appendChild(opt);
                    });
                    updateBatchInfo();
                })
                .catch(() => {
                    batchSelect.innerHTML = '<option value="">Error loading batches</option>';
                });
        });

        batchSelect.addEventListener('change', updateBatchInfo);
    }

    function updateBatchInfo() {
        if (!batchInfo) return;
        const opt = batchSelect.options[batchSelect.selectedIndex];
        if (!opt || !opt.dataset.qty) { clearBatchInfo(); return; }

        const qty   = parseInt(opt.dataset.qty);
        const color = opt.dataset.color;
        const days  = parseInt(opt.dataset.days);
        const expiry = opt.dataset.expiry;

        const colorMap = { success: 'success', warning: 'warning', info: 'info', danger: 'danger' };
        const cls = colorMap[color] || 'secondary';

        batchInfo.innerHTML = `
            <div class="d-flex gap-3 flex-wrap">
                <span class="badge bg-${cls} fs-sm py-1 px-2">
                    <i class="bi bi-calendar-x me-1"></i>Expires: ${expiry}
                    ${days >= 0 ? `(${days} days)` : '(EXPIRED)'}
                </span>
                <span class="badge bg-secondary fs-sm py-1 px-2">
                    <i class="bi bi-box-seam me-1"></i>Available: ${qty} units
                </span>
            </div>`;

        if (qtyInput) {
            qtyInput.max = qty;
            qtyInput.setAttribute('placeholder', `Max available: ${qty}`);
        }
    }

    function clearBatchInfo() {
        if (batchInfo) batchInfo.innerHTML = '';
        if (qtyInput) { qtyInput.max = ''; qtyInput.placeholder = ''; }
    }

    // ── Quantity validation on dispense form ─────────────────────
    if (qtyInput && batchSelect) {
        qtyInput.addEventListener('input', function () {
            const opt    = batchSelect.options[batchSelect.selectedIndex];
            const maxQty = opt ? parseInt(opt.dataset.qty || 0) : 0;
            if (maxQty > 0 && parseInt(this.value) > maxQty) {
                this.setCustomValidity(`Cannot exceed available quantity (${maxQty})`);
            } else {
                this.setCustomValidity('');
            }
        });
    }

    // ── Bootstrap form validation ────────────────────────────────
    document.querySelectorAll('.needs-validation').forEach(function (form) {
        form.addEventListener('submit', function (event) {
            if (!form.checkValidity()) {
                event.preventDefault();
                event.stopPropagation();
            }
            form.classList.add('was-validated');
        }, false);
    });

    // ── Procurement: add/remove drug rows ────────────────────────
    const addItemBtn    = document.getElementById('addProcurementItem');
    const itemsContainer= document.getElementById('procurementItems');

    if (addItemBtn && itemsContainer) {
        addItemBtn.addEventListener('click', function () {
            const idx  = itemsContainer.querySelectorAll('.procurement-row').length;
            const tmpl = document.getElementById('procurementRowTemplate').content.cloneNode(true);
            tmpl.querySelectorAll('[name]').forEach(function (el) {
                el.name = el.name.replace('__IDX__', idx);
            });
            itemsContainer.appendChild(tmpl);
        });

        itemsContainer.addEventListener('click', function (e) {
            if (e.target.closest('.remove-item-btn')) {
                e.target.closest('.procurement-row').remove();
            }
        });
    }

    // ── Tooltip init ─────────────────────────────────────────────
    document.querySelectorAll('[data-bs-toggle="tooltip"]').forEach(function (el) {
        new bootstrap.Tooltip(el);
    });
});
