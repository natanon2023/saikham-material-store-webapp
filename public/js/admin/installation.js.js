document.querySelector('input[name="installation_start_date"]').addEventListener('change', function () {
    const startDate = new Date(this.value);
    const days = {{ $project->estimated_work_days }} - 1;

    if (!this.value) return;

    startDate.setDate(startDate.getDate() + days);

    const yyyy = startDate.getFullYear();
    const mm = String(startDate.getMonth() + 1).padStart(2, '0');
    const dd = String(startDate.getDate()).padStart(2, '0');

    document.getElementById('installation_end_date').value = `${yyyy}-${mm}-${dd}`;
});