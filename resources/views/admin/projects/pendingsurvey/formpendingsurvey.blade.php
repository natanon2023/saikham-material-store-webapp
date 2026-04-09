@extends('layouts.admin')

@section('content')
<div class="main-content">

    @include('components.successanderror')

    <div class="boxmaterial" style="display:flex; justify-content:space-between; align-items:center;">
        <h3>เพิ่มข้อมูลงานใหม่</h3>
        <a href="{{ route('admin.projects.adminfulleventcalendarpage') }}" class="btn btn-primary">ย้อนกลับ</a>
    </div>

    @include('components.progress-steps1')

    <form action="{{ route('admin.projects.pendingsurvey') }}" method="POST" id="main-form">
        @csrf

        <div class="boxmaterial" style="margin-top:20px;">
            <span>1. ข้อมูลลูกค้าและนัดหมายสำรวจ</span>
        </div>
        
        <div class="box">
            <div class="box-control">

                <div class="form-group">
                    <label class="form-label">ชื่องาน <span style="color:red">*</span></label>
                    <div style="display:flex; align-items:center; gap:8px;">
                        <select name="project_name_id" class="form-select" required>
                            <option value="">เลือกชื่องาน</option>
                            @foreach ($projectname as $pn)
                                <option value="{{ $pn->id }}" {{ old('project_name_id') == $pn->id ? 'selected' : '' }}>
                                    {{ $pn->name }}
                                </option>
                            @endforeach
                        </select>
                        <a href="{{ route('admin.projects.formprojectname') }}" target="_blank"
                           class="btn-secondary"
                           style="padding:8px 12px; font-size:12px; text-decoration:none; white-space:nowrap; border-radius:0;">
                            + เพิ่ม
                        </a>
                    </div>
                </div>

                <div class="form-group">
                    <label class="form-label">ลูกค้า <span style="color:red">*</span></label>
                    <div style="display:flex; align-items:center; gap:8px;">
                        <select name="customer_id" class="form-select" required>
                            <option value="">เลือกชื่อลูกค้า</option>
                            @foreach ($customer as $cm)
                                <option value="{{ $cm->id }}" {{ old('customer_id') == $cm->id ? 'selected' : '' }}>
                                    คุณ {{ $cm->first_name }} {{ $cm->last_name }}
                                </option>
                            @endforeach
                        </select>
                        <a href="{{ route('admin.projects.formnewcustomer') }}" target="_blank"
                           class="btn-secondary"
                           style="padding:8px 12px; font-size:12px; text-decoration:none; white-space:nowrap; border-radius:0;">
                            + เพิ่ม
                        </a>
                    </div>
                </div>

                <div class="form-group">
                    <label class="form-label">วันและเวลานัดสำรวจ <span style="color:red">*</span></label>
                    <input type="datetime-local" name="survey_date" id="survey_date" class="form-input" value="{{ $defaultDate ? $defaultDate.'T08:00' : old('survey_date') }}"  min="{{ now()->format('Y-m-d\TH:i') }}" required>
                </div>

                <div class="form-group">
                    <label class="form-label">ช่างสำรวจ <span style="color:red">*</span></label>
                    <select name="assigned_surveyor_id" id="assigned_surveyor_id" class="form-select" required>
                        <option value="">เลือกช่างที่จะไปสำรวจ</option>
                        @foreach ($technician as $tc)
                            <option value="{{ $tc->id }}" {{ old('assigned_surveyor_id') == $tc->id ? 'selected' : '' }}>
                                ช่าง {{ $tc->name }} {{ $tc->last_name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="form-group">
                    <label class="form-label">ค่าแรงช่างสำรวจ <span style="color:red">*</span></label>
                    <input type="number" name="labor_cost_surveying" class="form-input" min="0" step="0.01" value="{{ old('labor_cost_surveying') }}" required>
                </div>

                <div class="form-group">
                    <label class="form-label">หมายเหตุ (ถ้ามี)</label>
                    <textarea name="note" class="form-input">{{ old('note') }}</textarea>
                </div>

            </div>
        </div>

        <div class="boxmaterial" style="margin-top:24px; display:flex; justify-content:space-between; align-items:center;">
            <span>2. ค่าใช้จ่ายเพิ่มเติม </span>
            <button type="button" class="btn btn-secondary" id="btn-add-expense">+ เพิ่มรายการ</button>
        </div>
        <div class="box">
            <div class="box-control">
                <div id="expense-header" style="display:none; grid-template-columns:2fr 1fr 1fr 2fr auto; gap:8px; font-size:12px; color:#999; padding:0 4px 6px; border-bottom:1px solid #eee;">
                    <span>รายการค่าใช้จ่าย *</span>
                    <span>จำนวนเงิน (บาท) *</span>
                    <span>วันที่ *</span>
                    <span>หมายเหตุ</span>
                    <span></span>
                </div>
                <div id="expense-rows"></div>
                <div id="expense-empty" style="text-align:center; color:#bbb; padding:20px 0; font-size:13px;">
                    ยังไม่มีรายการค่าใช้จ่าย — กดปุ่ม "+ เพิ่มรายการ" เพื่อเพิ่ม
                </div>
                <div id="expense-summary" style="display:none; justify-content:flex-end; padding-top:12px; border-top:1px solid #eee; margin-top:8px; font-weight:600;">
                    รวมค่าใช้จ่าย: <span id="expense-total" style="margin-left:8px; color:#E91E63;">0.00 บาท</span>
                </div>
            </div>
        </div>

        <div style="margin-top:24px; display:flex; justify-content:flex-end; gap:10px;">
            <button type="button" class="btn btn-primary" id="btn-clear-draft">ล้างแบบร่าง</button>
            <button type="submit" class="btn btn-secondary" style="padding:10px 32px; font-size:15px;">บันทึกข้อมูลทั้งหมด</button>
        </div>

    </form>
</div>

<template id="expense-row-template">
    <div class="expense-row" style="display:grid; grid-template-columns:2fr 1fr 1fr 2fr auto; gap:8px; align-items:start; margin-bottom:8px;">
        <div style="display:flex; align-items:center; gap:6px;">
            <select name="expenses[__INDEX__][expense_type_id]" class="form-select" required>
                <option value="">เลือกรายการค่าใช้จ่าย</option>
                @foreach ($expense as $et)
                    <option value="{{ $et->id }}">{{ $et->name }}</option>
                @endforeach
            </select>
            <a href="{{ route('admin.projects.formexpensetype') }}?from=create" target="_blank" class="btn-secondary" style="padding:8px 10px; font-size:12px; text-decoration:none; white-space:nowrap; border-radius:0; flex-shrink:0;">+ เพิ่ม</a>
        </div>
        <input type="number" name="expenses[__INDEX__][amount]" class="form-input expense-amount" placeholder="0.00" min="0" step="0.01" required>
        <input type="date" name="expenses[__INDEX__][expense_date]" class="form-input expense-date" required>
        <input type="text" name="expenses[__INDEX__][description]" class="form-input" placeholder="หมายเหตุ (ถ้ามี)">
        <button type="button" class="btn-icon btn-delete" onclick="removeExpenseRow(this)" title="ลบ"><i class="fas fa-trash"></i></button>
    </div>
</template>

<script>
(function () {
    'use strict';

    var COOKIE_KEY  = 'pending_survey_draft';
    var COOKIE_DAYS = 1;
    var REFRESH_URL = '{{ route("admin.projects.getFormOptions") }}';

    var form      = document.getElementById('main-form');
    var rowsEl    = document.getElementById('expense-rows');
    var emptyEl   = document.getElementById('expense-empty');
    var summaryEl = document.getElementById('expense-summary');
    var headerEl  = document.getElementById('expense-header');
    var totalEl   = document.getElementById('expense-total');
    var tmpl      = document.getElementById('expense-row-template');
    var btnAdd    = document.getElementById('btn-add-expense');
    var btnClear  = document.getElementById('btn-clear-draft');
    
    var surveyInput = document.getElementById('survey_date');
    var surveyorSelect = document.getElementById('assigned_surveyor_id');

    var expenseIndex = 0;
    var allSchedules = @json($schedules ?? []);

    function updateTechnicianOptions() {
        var dateVal = surveyInput.value;
        if (!dateVal) return;

        var targetDate = dateVal.split('T')[0];
        var currentSelected = surveyorSelect.value;
        var shouldClearSelection = false;

        Array.from(surveyorSelect.options).forEach(function(opt) {
            if (opt.value === "") return;

            var techId = opt.value;
            var isBusy = allSchedules.some(function(sch) {
                return sch.tech_id === techId && sch.date === targetDate;
            });

            var originalText = opt.text.replace(/ \(ติดงาน\)/g, '');

            if (isBusy) {
                opt.text = originalText + " (ติดงาน)";
                opt.disabled = true;
                opt.style.color = '#999'; 
                opt.style.backgroundColor = '#f1f1f1'; 
                if (currentSelected === techId) {
                    shouldClearSelection = true;
                }
            } else {
                opt.text = originalText;
                opt.disabled = false;
                opt.style.color = '';
                opt.style.backgroundColor = '';
            }
        });

        if (shouldClearSelection) {
            surveyorSelect.value = "";
        }
    }

    function setCookie(name, value, days) {
        var expires = new Date(Date.now() + days * 864e5).toUTCString();
        document.cookie = name + '=' + encodeURIComponent(value)
                        + '; expires=' + expires
                        + '; path=/; SameSite=Lax';
    }

    function getCookie(name) {
        var result = document.cookie.split('; ').reduce(function (r, c) {
            var parts = c.split('=');
            return parts[0] === name ? parts.slice(1).join('=') : r;
        }, '');
        return result ? decodeURIComponent(result) : null;
    }

    function deleteCookie(name) {
        document.cookie = name + '=; expires=Thu, 01 Jan 1970 00:00:00 UTC; path=/';
    }

    function getSurveyDateOnly() {
        var val = surveyInput.value;
        if (!val) return '';
        return val.split('T')[0];
    }

    function saveDraft() {
        var data = {
            project_name_id:      form.querySelector('[name="project_name_id"]').value,
            customer_id:          form.querySelector('[name="customer_id"]').value,
            survey_date:          surveyInput.value,
            assigned_surveyor_id: form.querySelector('[name="assigned_surveyor_id"]').value,
            labor_cost_surveying: form.querySelector('[name="labor_cost_surveying"]').value,
            note:                 form.querySelector('[name="note"]').value,
            expenses:             []
        };

        rowsEl.querySelectorAll('.expense-row').forEach(function (row) {
            data.expenses.push({
                expense_type_id: row.querySelector('select').value,
                amount:          row.querySelector('.expense-amount').value,
                expense_date:    row.querySelector('.expense-date').value,
                description:     row.querySelector('input[type="text"]').value,
            });
        });

        setCookie(COOKIE_KEY, JSON.stringify(data), COOKIE_DAYS);
    }

    function loadDraft() {
        var raw = getCookie(COOKIE_KEY);
        if (!raw) return;

        try {
            var data = JSON.parse(raw);

            form.querySelector('[name="project_name_id"]').value      = data.project_name_id      || '';
            form.querySelector('[name="customer_id"]').value          = data.customer_id          || '';
            form.querySelector('[name="labor_cost_surveying"]').value = data.labor_cost_surveying || '';
            form.querySelector('[name="note"]').value                 = data.note                 || '';

            if (!surveyInput.value && data.survey_date) {
                surveyInput.value = data.survey_date;
            }

            updateTechnicianOptions();

            if (data.assigned_surveyor_id) {
                var surveyorOpt = form.querySelector('[name="assigned_surveyor_id"] option[value="'+data.assigned_surveyor_id+'"]');
                if (surveyorOpt && !surveyorOpt.disabled) {
                    form.querySelector('[name="assigned_surveyor_id"]').value = data.assigned_surveyor_id;
                }
            }

            (data.expenses || []).forEach(function (exp) {
                addExpenseRow(exp);
            });
        } catch (e) {
            deleteCookie(COOKIE_KEY);
        }
    }

    function clearDraft() {
        deleteCookie(COOKIE_KEY);
    }

    function addExpenseRow(values) {
        var html = tmpl.innerHTML.replaceAll('__INDEX__', expenseIndex++);
        var wrap = document.createElement('div');
        wrap.innerHTML = html;
        var row = wrap.firstElementChild;

        var dateInput = row.querySelector('.expense-date');

        if (values && values.expense_date) {
            dateInput.value = values.expense_date;
        } else {
            dateInput.value = getSurveyDateOnly();
        }

        row.querySelectorAll('input, select').forEach(function (el) {
            el.addEventListener('input',  function () { recalcTotal(); saveDraft(); });
            el.addEventListener('change', function () { recalcTotal(); saveDraft(); });
        });

        if (values) {
            row.querySelector('select').value             = values.expense_type_id || '';
            row.querySelector('.expense-amount').value    = values.amount          || '';
            row.querySelector('input[type="text"]').value = values.description     || '';
        }

        rowsEl.appendChild(row);
        updateVisibility();
        recalcTotal();
    }

    window.removeExpenseRow = function(btn) {
        btn.closest('.expense-row').remove();
        updateVisibility();
        recalcTotal();
        saveDraft();
    };

    function updateVisibility() {
        var has = rowsEl.children.length > 0;
        emptyEl.style.display   = has ? 'none'  : 'block';
        summaryEl.style.display = has ? 'flex'  : 'none';
        headerEl.style.display  = has ? 'grid'  : 'none';
    }

    function recalcTotal() {
        var sum = 0;
        rowsEl.querySelectorAll('.expense-amount').forEach(function (inp) {
            sum += parseFloat(inp.value) || 0;
        });
        totalEl.textContent = sum.toLocaleString('th-TH', { minimumFractionDigits: 2 }) + ' บาท';
    }

    function updateSelect(selectEl, items, placeholder, labelFn) {
        if (!selectEl) return;
        var current = selectEl.value;
        selectEl.innerHTML = '<option value="">' + placeholder + '</option>';
        items.forEach(function (item) {
            var opt   = document.createElement('option');
            opt.value = item.id;
            opt.text  = labelFn ? labelFn(item) : item.name;
            selectEl.appendChild(opt);
        });
        
        if (selectEl.id === 'assigned_surveyor_id') {
            updateTechnicianOptions();
        }

        var matchingOpt = selectEl.querySelector('option[value="'+current+'"]');
        if (matchingOpt && !matchingOpt.disabled) {
            selectEl.value = current;
        }
    }

    surveyInput.addEventListener('change', function() {
        var newDate = getSurveyDateOnly();
        if (newDate) {
            rowsEl.querySelectorAll('.expense-date').forEach(function(dateInp) {
                if (!dateInp.value) {
                    dateInp.value = newDate;
                }
            });
        }
        updateTechnicianOptions();
        saveDraft();
    });

    btnAdd.addEventListener('click', function () {
        addExpenseRow(null);
        saveDraft();
    });

    btnClear.addEventListener('click', function () {
        if (confirm('ล้างข้อมูลแบบร่างที่กรอกไว้ทั้งหมด?')) {
            clearDraft();
            location.reload();
        }
    });

    form.querySelectorAll('input, select, textarea').forEach(function (el) {
        if (el.id !== 'survey_date') {
            el.addEventListener('input',  saveDraft);
            el.addEventListener('change', saveDraft);
        }
    });

    form.addEventListener('submit', clearDraft);

    window.addEventListener('focus', function () {
        fetch(REFRESH_URL)
            .then(function (r) { return r.json(); })
            .then(function (data) {
                updateSelect(
                    form.querySelector('[name="project_name_id"]'),
                    data.projectnames, 'เลือกชื่องาน'
                );
                updateSelect(
                    form.querySelector('[name="customer_id"]'),
                    data.customers, 'เลือกชื่อลูกค้า',
                    function (i) { return 'คุณ ' + i.first_name + ' ' + i.last_name; }
                );
                updateSelect(
                    form.querySelector('[name="assigned_surveyor_id"]'),
                    data.technicians, 'เลือกช่างที่จะไปสำรวจ',
                    function (i) { return 'ช่าง ' + i.name + ' ' + i.last_name; }
                );
                rowsEl.querySelectorAll('.expense-row select').forEach(function (sel) {
                    updateSelect(sel, data.expensetypes, 'เลือกรายการค่าใช้จ่าย');
                });
            })
            .catch(function () {});
    });

    document.addEventListener('DOMContentLoaded', function() {
        loadDraft();
        updateTechnicianOptions(); 
        saveDraft();
    });

})();

    document.addEventListener('DOMContentLoaded', function() {
        var now = new Date();
        var pad = function(n) { return String(n).padStart(2, '0'); };
        var minVal = now.getFullYear() + '-'
                + pad(now.getMonth() + 1) + '-'
                + pad(now.getDate()) + 'T'
                + pad(now.getHours()) + ':'
                + pad(now.getMinutes());
        surveyInput.setAttribute('min', minVal);

        loadDraft();
        updateTechnicianOptions();
        saveDraft();
    });
</script>
@endsection