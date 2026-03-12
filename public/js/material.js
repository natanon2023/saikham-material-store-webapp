// จัดการการแสดงรายละเอียดตามประเภทวัสดุ
document.getElementById('material_type').addEventListener('change', function () {
    const materialType = this.value;
    const allDetails = document.querySelectorAll('.material-details');

    // ซ่อนทุกรายละเอียด
    allDetails.forEach(detail => {
        detail.style.display = 'none';
        detail.classList.remove('show');
    });

    // แสดงรายละเอียดตามประเภทที่เลือก
    if (materialType) {
        const targetDetail = document.getElementById(materialType + '_details');
        if (targetDetail) {
            targetDetail.style.display = 'block';
            targetDetail.classList.add('show');
        }
    }
});

// จัดการการส่งฟอร์ม
document.getElementById('materialForm').addEventListener('submit', function (e) {
    const formData = new FormData(this);
    const materialType = formData.get('material_type');

    // ตรวจสอบข้อมูลที่จำเป็น
    if (!materialType) {
        alert('กรุณาเลือกประเภทวัสดุ');
        e.preventDefault();
        return;
    }

    // ตรวจสอบข้อมูลเฉพาะตามประเภท
    let isValid = true;
    let errorMessage = '';

    switch (materialType) {
        case 'aluminium':
            if (!formData.get('aluminium_profile_type_id')) {
                errorMessage = 'กรุณาเลือกประเภทโปรไฟล์อลูมิเนียม';
                isValid = false;
            }
            break;
        case 'glass':
            if (!formData.get('glass_type_id')) {
                errorMessage = 'กรุณาเลือกประเภทกระจก';
                isValid = false;
            }
            break;
        case 'accessory':
            if (!formData.get('accessory_type_id')) {
                errorMessage = 'กรุณาเลือกประเภทอุปกรณ์เสริม';
                isValid = false;
            }
            break;
        case 'tool':
            if (!formData.get('tool_type_id')) {
                errorMessage = 'กรุณาเลือกประเภทเครื่องมือ';
                isValid = false;
            }
            break;
        case 'consumable':
            if (!formData.get('consumable_name')) {
                errorMessage = 'กรุณาระบุชื่อวัสดุสิ้นเปลือง';
                isValid = false;
            }
            break;
    }

    if (!isValid) {
        alert(errorMessage);
        e.preventDefault();
        return;
    }

    // ให้ฟอร์มส่งไปยัง server
    // this.submit() จะทำงานอัตโนมัติ
});

// ฟังก์ชันสำหรับการคำนวณราคาขายอัตโนมัติ (เพิ่ม 20% จากราคาต้นทุน)
function calculateSellingPrice(costInput, sellingInput) {
    if (costInput && sellingInput) {
        costInput.addEventListener('input', function () {
            const cost = parseFloat(this.value) || 0;
            const sellingPrice = cost * 1.2; // เพิ่ม 20%
            sellingInput.value = sellingPrice.toFixed(2);
        });
    }
}

// เรียกใช้ฟังก์ชันคำนวณสำหรับทุกประเภท
document.addEventListener('DOMContentLoaded', function () {
    // ตั้งค่าวันที่เป็นวันปัจจุบัน
    const today = new Date().toISOString().split('T')[0];
    const purchaseDateInput = document.getElementById('purchase_date');
    if (purchaseDateInput) {
        purchaseDateInput.value = today;
    }

    // อลูมิเนียม
    calculateSellingPrice(
        document.getElementById('cost_per_unit'),
        document.getElementById('selling_price_per_unit')
    );

    // กระจก
    calculateSellingPrice(
        document.getElementById('glass_cost_per_unit'),
        document.getElementById('glass_selling_price_per_unit')
    );

    // อุปกรณ์เสริม
    calculateSellingPrice(
        document.getElementById('accessory_cost_per_unit'),
        document.getElementById('accessory_selling_price_per_unit')
    );

    // วัสดุสิ้นเปลือง
    calculateSellingPrice(
        document.getElementById('consumable_cost_per_unit'),
        document.getElementById('consumable_selling_price_per_unit')
    );
});




