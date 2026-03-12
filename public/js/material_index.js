function showSubType(type) {
    // ซ่อนทั้งหมด
    document.getElementById('aluminium_subtype').style.display = 'none';
    document.getElementById('glass_subtype').style.display = 'none';
    document.getElementById('accessory_subtype').style.display = 'none';
    document.getElementById('tool_subtype').style.display = 'none';

    // แสดงเฉพาะที่เลือก
    if (type === 'aluminium') {
        document.getElementById('aluminium_subtype').style.display = 'block';
    } else if (type === 'glass') {
        document.getElementById('glass_subtype').style.display = 'block';
    } else if (type === 'accessory') {
        document.getElementById('accessory_subtype').style.display = 'block';
    } else if (type === 'tool') {
        document.getElementById('tool_subtype').style.display = 'block';
    }
}

// ฟังก์ชันสำหรับ searchable dropdown
function setupSearchableDropdown(searchId, dropdownId, hiddenInputId) {
    const searchInput = document.getElementById(searchId);
    const dropdown = document.getElementById(dropdownId);
    const hiddenInput = document.getElementById(hiddenInputId);

    if (!searchInput) return;

    // แสดง dropdown เมื่อคลิกที่ input
    searchInput.addEventListener('focus', function () {
        dropdown.classList.add('show');
    });

    // ซ่อน dropdown เมื่อคลิกที่อื่น
    document.addEventListener('click', function (e) {
        if (!searchInput.contains(e.target) && !dropdown.contains(e.target)) {
            dropdown.classList.remove('show');
        }
    });

    // ค้นหาเมื่อพิมพ์
    searchInput.addEventListener('input', function () {
        const filter = this.value.toLowerCase();
        const items = dropdown.querySelectorAll('.dropdown-item');

        items.forEach(item => {
            const text = item.textContent.toLowerCase();
            if (text.includes(filter)) {
                item.style.display = '';
            } else {
                item.style.display = 'none';
            }
        });
    });

    // เลือกรายการ
    dropdown.querySelectorAll('.dropdown-item').forEach(item => {
        item.addEventListener('click', function () {
            const value = this.getAttribute('data-value');
            const text = this.textContent;

            searchInput.value = text;
            hiddenInput.value = value;
            dropdown.classList.remove('show');
        });
    });
}

// เริ่มต้น searchable dropdowns
setupSearchableDropdown('aluminium_search', 'aluminium_dropdown', 'aluminium_type_id');
setupSearchableDropdown('glass_search', 'glass_dropdown', 'glass_type_id');
setupSearchableDropdown('accessory_search', 'accessory_dropdown', 'accessory_type_id');
setupSearchableDropdown('tool_search', 'tool_dropdown', 'tool_type_id');

