<!DOCTYPE html>
<html lang="mn">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ажилчдын Санхүүгийн Бүртгэл</title>
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
</head>
<body class="bg-gray-50 text-gray-800 p-4 md:p-8 min-h-screen pb-24">

    <div class="max-w-4xl mx-auto">
        <header class="mb-6 border-b border-gray-200 pb-4 text-center md:text-left">
            <h1 class="text-2xl font-bold text-gray-900">Ажилчдын Санхүүгийн Бүртгэл</h1>
            <p class="text-sm text-gray-500 mt-1">Орлого, зарлагыг ажилтан тус бүрээр шүүж засах систем</p>
        </header>

        <div class="bg-white p-4 rounded-2xl shadow-xs border border-gray-100 mb-6 flex flex-col md:flex-row md:items-center justify-between gap-4">
            <div class="w-full md:w-1/2">
                <label class="block text-xs font-bold text-gray-400 uppercase tracking-wider mb-1">Идэвхтэй Ажилтан Сонгох</label>
                <select id="main-employee-select" onchange="onEmployeeChange()" class="w-full p-3 bg-gray-50 border border-gray-200 rounded-xl font-semibold text-gray-700 focus:outline-none focus:border-blue-500 focus:bg-white transition">
                    <option value="">-- Бүх ажилчид хамтдаа --</option>
                </select>
            </div>
            <div class="text-right flex items-end justify-between md:justify-end gap-2">
                <button onclick="openModal('employee-list-modal')" class="text-sm bg-gray-100 hover:bg-gray-200 text-gray-700 px-4 py-2.5 rounded-xl font-medium transition cursor-pointer">
                    Ажилчдын Жагсаалт / Засах / Хасах
                </button>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
            <div class="bg-white p-5 rounded-2xl shadow-xs border border-gray-100">
                <span id="summary-title-income" class="text-xs text-gray-400 font-bold uppercase tracking-wider">Нийт Орлого</span>
                <div id="total-income" class="text-2xl font-bold text-green-600 mt-1">0 ₮</div>
            </div>
            <div class="bg-white p-5 rounded-2xl shadow-xs border border-gray-100">
                <span id="summary-title-expense" class="text-xs text-gray-400 font-bold uppercase tracking-wider">Нийт Зарлага</span>
                <div id="total-expense" class="text-2xl font-bold text-red-600 mt-1">0 ₮</div>
            </div>
            <div class="bg-white p-5 rounded-2xl shadow-xs border border-gray-100">
                <span id="summary-title-balance" class="text-xs text-gray-400 font-bold uppercase tracking-wider">Үлдэгдэл</span>
                <div id="balance" class="text-2xl font-bold text-blue-600 mt-1">0 ₮</div>
            </div>
        </div>

        <div class="bg-white p-6 rounded-2xl shadow-xs border border-gray-100">
            <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-3 mb-4">
                <h2 class="font-bold text-lg text-gray-800">Гүйлгээний түүх</h2>
                
                <div class="flex items-center space-x-2 text-xs text-gray-500">
                    <span>Харуулах:</span>
                    <select id="per-page-select" onchange="onPerPageChange()" class="border border-gray-200 p-1.5 rounded-lg bg-gray-50 font-medium focus:outline-none focus:border-blue-500">
                        <option value="20" selected>20 мөрөөр</option>
                        <option value="50">50 мөрөөр</option>
                        <option value="100">100 мөрөөр</option>
                        <option value="all">Бүгдийг</option>
                    </select>
                </div>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-sm text-left">
                    <thead>
                        <tr class="text-gray-400 border-b border-gray-100 font-medium">
                            <th class="pb-3">Ажилтан</th>
                            <th class="pb-3">Огноо</th>
                            <th class="pb-3">Тайлбар</th>
                            <th class="pb-3 text-right">Дүн</th>
                            <th class="pb-3 text-center">Үйлдэл</th>
                        </tr>
                    </thead>
                    <tbody id="tx-rows" class="divide-y divide-gray-50"></tbody>
                </table>
            </div>

            <div class="flex flex-col sm:flex-row justify-between items-center gap-4 border-t border-gray-100 pt-4 mt-4 text-xs">
                <div id="pagination-info" class="text-gray-400 font-medium">Харуулж байна: 0-0 / Нийт: 0</div>
                <div id="pagination-buttons" class="flex items-center space-x-1">
                    </div>
            </div>
        </div>
    </div>

    <div class="fixed bottom-6 right-6 flex flex-col items-end space-y-3 z-40">
        <button onclick="openAddEmployeeModal()" class="bg-white hover:bg-gray-50 text-gray-700 shadow-md p-3 rounded-full flex items-center justify-center border border-gray-100 transition cursor-pointer group">
            <span class="text-xs font-semibold px-2 hidden group-hover:block">Ажилтан нэмэх</span>
            <span class="material-icons text-xl">person_add</span>
        </button>
        <button onclick="openTxModal()" class="bg-gray-950 hover:bg-gray-800 text-white shadow-xl p-4 rounded-full flex items-center justify-center transition cursor-pointer transform hover:scale-105">
            <span class="material-icons text-2xl">add</span>
        </button>
    </div>


    <div id="add-tx-modal" class="fixed inset-0 bg-black/40 backdrop-blur-xs hidden justify-center items-center p-4 z-50">
        <div class="bg-white w-full max-w-md p-6 rounded-2xl shadow-2xl relative">
            <h3 id="tx-modal-title" class="text-lg font-bold text-gray-900 mb-4">Шинэ гүйлгээ бүртгэх</h3>
            <form id="tx-form" class="space-y-4 text-sm">
                <input type="hidden" id="tx-id">
                <div>
                    <label class="block text-xs font-medium text-gray-500 mb-1">Ажилтан</label>
                    <select id="modal-tx-employee" required class="w-full p-2.5 border border-gray-200 rounded-xl focus:outline-none focus:border-blue-500"></select>
                </div>
                <div>
                    <label class="block text-xs font-medium text-gray-500 mb-1">Огноо</label>
                    <input type="date" id="tx-date" required class="w-full p-2.5 border border-gray-200 rounded-xl focus:outline-none focus:border-blue-500">
                </div>
                <div>
                    <label class="block text-xs font-medium text-gray-500 mb-1">Тайлбар</label>
                    <input type="text" id="tx-desc" placeholder="Тайлбар бичих" required class="w-full p-2.5 border border-gray-200 rounded-xl focus:outline-none focus:border-blue-500">
                </div>
                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="block text-xs font-medium text-gray-500 mb-1">Төрөл</label>
                        <select id="tx-type" class="w-full p-2.5 border border-gray-200 rounded-xl focus:outline-none focus:border-blue-500">
                            <option value="income">Орлого (+)</option>
                            <option value="expense">Зарлага (-)</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-xs font-medium text-gray-500 mb-1">Дүн (₮)</label>
                        <input type="number" id="tx-amount" min="1" placeholder="0" required class="w-full p-2.5 border border-gray-200 rounded-xl focus:outline-none focus:border-blue-500">
                    </div>
                </div>
                <div class="flex space-x-2 pt-2">
                    <button type="button" onclick="closeModal('add-tx-modal')" class="w-1/2 bg-gray-100 text-gray-700 py-2.5 rounded-xl font-medium hover:bg-gray-200 transition cursor-pointer">Цуцлах</button>
                    <button type="submit" class="w-1/2 bg-gray-950 text-white py-2.5 rounded-xl font-semibold hover:bg-gray-800 transition cursor-pointer">Хадгалах</button>
                </div>
            </form>
        </div>
    </div>

    <div id="add-employee-modal" class="fixed inset-0 bg-black/40 backdrop-blur-xs hidden justify-center items-center p-4 z-50">
        <div class="bg-white w-full max-w-sm p-6 rounded-2xl shadow-2xl relative">
            <h3 id="emp-modal-title" class="text-lg font-bold text-gray-900 mb-4">Шинэ ажилтан нэмэх</h3>
            <form id="emp-form" class="space-y-4 text-sm">
                <input type="hidden" id="emp-id">
                <div>
                    <label class="block text-xs font-medium text-gray-500 mb-1">Ажилтны нэр</label>
                    <input type="text" id="emp-name" placeholder="Жишээ нь: Болд" required class="w-full p-2.5 border border-gray-200 rounded-xl focus:outline-none focus:border-blue-500">
                </div>
                <div>
                    <label class="block text-xs font-medium text-gray-500 mb-1">Албан тушаал</label>
                    <input type="text" id="emp-position" placeholder="Жишээ нь: Нярав" class="w-full p-2.5 border border-gray-200 rounded-xl focus:outline-none focus:border-blue-500">
                </div>
                <div class="flex space-x-2 pt-2">
                    <button type="button" onclick="closeModal('add-employee-modal')" class="w-1/2 bg-gray-100 text-gray-700 py-2.5 rounded-xl font-medium hover:bg-gray-200 transition cursor-pointer">Цуцлах</button>
                    <button type="submit" class="w-1/2 bg-blue-600 text-white py-2.5 rounded-xl font-semibold hover:bg-blue-500 transition cursor-pointer">Хадгалах</button>
                </div>
            </form>
        </div>
    </div>

    <div id="employee-list-modal" class="fixed inset-0 bg-black/40 backdrop-blur-xs hidden justify-center items-center p-4 z-50">
        <div class="bg-white w-full max-w-md p-6 rounded-2xl shadow-2xl relative max-h-[80vh] flex flex-col">
            <div class="flex justify-between items-center mb-4">
                <h3 class="text-lg font-bold text-gray-900">Нийт ажилчид</h3>
                <button onclick="closeModal('employee-list-modal')" class="text-gray-400 hover:text-gray-600 font-bold cursor-pointer">Хаах</button>
            </div>
            <div class="overflow-y-auto flex-1">
                <table class="w-full text-sm text-left">
                    <thead>
                        <tr class="text-gray-400 border-b border-gray-100 font-medium">
                            <th class="pb-2">Нэр</th>
                            <th class="pb-2">Албан тушаал</th>
                            <th class="pb-2 text-right">Үйлдэл</th>
                        </tr>
                    </thead>
                    <tbody id="emp-rows" class="divide-y divide-gray-50"></tbody>
                </table>
            </div>
        </div>
    </div>


    <script>
        let allTransactions = []; 
        let allEmployees = [];
        
        // ХУУДАСЛАЛТЫН ГЛOБАЛ СТЭЙТ (PAGINATION STATE)
        let currentPage = 1;
        let rowsPerPage = 20; 

        function openModal(id) { document.getElementById(id).classList.remove('hidden'); document.getElementById(id).classList.add('flex'); }
        function closeModal(id) { document.getElementById(id).classList.add('hidden'); document.getElementById(id).classList.remove('flex'); }

        function openTxModal() {
            document.getElementById('tx-id').value = ''; 
            document.getElementById('tx-desc').value = '';
            document.getElementById('tx-amount').value = '';
            document.getElementById('tx-date').value = new Date().toISOString().split('T')[0];
            document.getElementById('tx-modal-title').innerText = "Шинэ гүйлгээ бүртгэх";
            
            const currentSelected = document.getElementById('main-employee-select').value;
            document.getElementById('modal-tx-employee').value = currentSelected;
            openModal('add-tx-modal');
        }

        function openAddEmployeeModal() {
            document.getElementById('emp-id').value = '';
            document.getElementById('emp-name').value = '';
            document.getElementById('emp-position').value = '';
            document.getElementById('emp-modal-title').innerText = "Шинэ ажилтан нэмэх";
            openModal('add-employee-modal');
        }

        function editEmployee(id) {
            const emp = allEmployees.find(e => e.id == id);
            if (!emp) return;
            document.getElementById('emp-id').value = emp.id;
            document.getElementById('emp-name').value = emp.name;
            document.getElementById('emp-position').value = emp.position;
            document.getElementById('emp-modal-title').innerText = "Ажилтны мэдээлэл засах";
            closeModal('employee-list-modal');
            openModal('add-employee-modal');
        }

        function editTransaction(id) {
            const tx = allTransactions.find(t => t.id == id);
            if (!tx) return;
            document.getElementById('tx-id').value = tx.id;
            document.getElementById('modal-tx-employee').value = tx.employee_id;
            document.getElementById('tx-date').value = tx.date;
            document.getElementById('tx-desc').value = tx.description;
            document.getElementById('tx-type').value = tx.type;
            document.getElementById('tx-amount').value = parseFloat(tx.amount);
            document.getElementById('tx-modal-title').innerText = "Гүйлгээ засах";
            openModal('add-tx-modal');
        }

        async function fetchEmployees() {
            const res = await fetch('api.php?action=employees');
            allEmployees = await res.json();
            
            const mainSelect = document.getElementById('main-employee-select');
            const modalSelect = document.getElementById('modal-tx-employee');
            const lastSelected = mainSelect.value;

            mainSelect.innerHTML = '<option value="">-- Бүх ажилчид хамтдаа --</option>';
            modalSelect.innerHTML = '<option value="">-- Ажилтан сонгох --</option>';
            
            const rowsContainer = document.getElementById('emp-rows');
            rowsContainer.innerHTML = '';

            allEmployees.forEach(emp => {
                const opt1 = document.createElement('option'); opt1.value = emp.id; opt1.innerText = emp.name;
                mainSelect.appendChild(opt1);

                const opt2 = document.createElement('option'); opt2.value = emp.id; opt2.innerText = emp.name;
                modalSelect.appendChild(opt2);

                const row = document.createElement('tr');
                row.className = "hover:bg-gray-50/50";
                row.innerHTML = `
                    <td class="py-3 font-semibold text-gray-900">${emp.name}</td>
                    <td class="py-3 text-gray-500">${emp.position || '-'}</td>
                    <td class="py-3 text-right space-x-2">
                        <button onclick="editEmployee(${emp.id})" class="text-xs text-blue-500 hover:text-blue-700 font-medium cursor-pointer">Засах</button>
                        <button onclick="deleteEmployee(${emp.id})" class="text-xs text-red-500 hover:text-red-700 font-medium cursor-pointer">Хасах</button>
                    </td>
                `;
                rowsContainer.appendChild(row);
            });

            if (lastSelected && allEmployees.some(e => e.id == lastSelected)) {
                mainSelect.value = lastSelected;
            }
        }

        async function fetchTransactions() {
            const res = await fetch('api.php');
            allTransactions = await res.json();
            renderData();
        }

        // ӨГӨГДЛИЙГ ШҮҮЖ, ХУУДАСЛАЖ ДЭЛГЭЦЭНД ХАРУУЛАХ ҮНДСЭН ФУНКЦ
        function renderData() {
            const selectedEmployeeId = document.getElementById('main-employee-select').value;
            const rowsContainer = document.getElementById('tx-rows');
            rowsContainer.innerHTML = '';
            
            let totalIncome = 0; let totalExpense = 0;

            // 1. Сонгосон ажилтнаар шүүх хэсэг
            const filteredTx = selectedEmployeeId 
                ? allTransactions.filter(t => t.employee_id == selectedEmployeeId)
                : allTransactions;

            // 2. Нийт тоог бодож гаргах (Шүүлтүүрийн дараах нийт орлого, зарлага)
            filteredTx.forEach(t => {
                const amount = parseFloat(t.amount);
                if (t.type === 'income') totalIncome += amount;
                else totalExpense += amount;
            });

            // 3. ХУУДАСЛАЛТЫН ТООЦООЛОЛ (PAGINATION LOGIC)
            const totalItems = filteredTx.length;
            // Хэрэв 'all' сонгогдсон бол rowsPerPage-ийг нийт тоотой тэнцүүлнэ
            const limit = rowsPerPage === 'all' ? totalItems : parseInt(rowsPerPage);
            const totalPages = Math.ceil(totalItems / limit) || 1;

            // Хэрэв хуудасны тоо хэтэрсэн байвал 1-р хуудас руу шилжүүлнэ
            if (currentPage > totalPages) currentPage = 1;

            const startIndex = (currentPage - 1) * limit;
            const endIndex = Math.min(startIndex + limit, totalItems);

            // Одоогийн хуудсанд харагдах өгөгдөл
            const pageItems = filteredTx.slice(startIndex, endIndex);

            if (pageItems.length === 0) {
                rowsContainer.innerHTML = `<tr><td colspan="5" class="text-center py-6 text-gray-400">Гүйлгээ бүртгэгдээгүй байна.</td></tr>`;
            }

            // Хүснэгт зурах
            pageItems.forEach(t => {
                const amount = parseFloat(t.amount);
                const isIncome = t.type === 'income';

                const row = document.createElement('tr');
                row.className = 'hover:bg-gray-50/50';
                row.innerHTML = `
                    <td class="py-3 font-semibold text-gray-800">${t.employee_name}</td>
                    <td class="py-3 text-gray-400">${t.date}</td>
                    <td class="py-3 text-gray-600">${t.description}</td>
                    <td class="py-3 text-right font-bold ${isIncome ? 'text-green-600' : 'text-red-600'}">
                        ${isIncome ? '+' : '-'}${amount.toLocaleString()} ₮
                    </td>
                    <td class="py-3 text-center">
                        <button onclick="editTransaction(${t.id})" class="text-xs bg-gray-100 text-gray-600 px-2 py-1 rounded-md hover:bg-gray-200 cursor-pointer">Засах</button>
                    </td>
                `;
                rowsContainer.appendChild(row);
            });

            // Товчоо хэсгүүдийг шинэчлэх
            document.getElementById('total-income').innerText = totalIncome.toLocaleString() + ' ₮';
            document.getElementById('total-expense').innerText = totalExpense.toLocaleString() + ' ₮';
            const balance = totalIncome - totalExpense;
            const balanceEl = document.getElementById('balance');
            balanceEl.innerText = balance.toLocaleString() + ' ₮';
            balanceEl.className = balance >= 0 ? "text-2xl font-bold text-blue-600 mt-1" : "text-2xl font-bold text-red-500 mt-1";
            
            const empName = selectedEmployeeId ? document.getElementById('main-employee-select').options[document.getElementById('main-employee-select').selectedIndex].text : "Нийт";
            document.getElementById('summary-title-income').innerText = empName + " Орлого";
            document.getElementById('summary-title-expense').innerText = empName + " Зарлага";
            document.getElementById('summary-title-balance').innerText = empName + " Үлдэгдэл";

            // Хуудаслалтын Текст мэдээлэл шинэчлэх
            document.getElementById('pagination-info').innerText = totalItems > 0 
                ? `Харуулж байна: ${startIndex + 1}-${endIndex} / Нийт: ${totalItems}`
                : `Харуулж байна: 0-0 / Нийт: 0`;

            // Хуудаслалтын товчлууруудыг зурах (Дэвшилтэт хэлбэрээр)
            setupPaginationButtons(totalPages);
        }

        // ХУУДАСНЫ ТОВЧНУУДЫГ УХААЛАГАР ЗУРАХ ФУНКЦ
        function setupPaginationButtons(totalPages) {
            const container = document.getElementById('pagination-buttons');
            container.innerHTML = '';

            if (totalPages <= 1) return; // Хэрэв 1-хэн хуудастай бол товч харуулахгүй

            // Өмнөх хуудас руу буцах товч
            const prevBtn = document.createElement('button');
            prevBtn.innerHTML = '<span class="material-icons text-sm">chevron_left</span>';
            prevBtn.disabled = currentPage === 1;
            prevBtn.className = `p-1.5 rounded-lg border border-gray-200 flex items-center justify-center cursor-pointer ${currentPage === 1 ? 'opacity-40 cursor-not-allowed' : 'hover:bg-gray-100'}`;
            prevBtn.onclick = () => { if(currentPage > 1) { currentPage--; renderData(); } };
            container.appendChild(prevBtn);

            // Хуудасны дугаартай товчнууд (Хэт олон бол хязгаарлана)
            let startPage = Math.max(1, currentPage - 2);
            let endPage = Math.min(totalPages, startPage + 4);
            if (endPage - startPage < 4) startPage = Math.max(1, endPage - 4);

            for (let i = startPage; i <= endPage; i++) {
                const pageBtn = document.createElement('button');
                pageBtn.innerText = i;
                pageBtn.className = `px-3 py-1.5 rounded-lg border font-semibold text-xs cursor-pointer ${currentPage === i ? 'bg-gray-950 text-white border-gray-950' : 'border-gray-200 hover:bg-gray-100 text-gray-600'}`;
                pageBtn.onclick = () => { currentPage = i; renderData(); };
                container.appendChild(pageBtn);
            }

            // Дараах хуудас руу шилжих товч
            const nextBtn = document.createElement('button');
            nextBtn.innerHTML = '<span class="material-icons text-sm">chevron_right</span>';
            nextBtn.disabled = currentPage === totalPages;
            nextBtn.className = `p-1.5 rounded-lg border border-gray-200 flex items-center justify-center cursor-pointer ${currentPage === totalPages ? 'opacity-40 cursor-not-allowed' : 'hover:bg-gray-100'}`;
            nextBtn.onclick = () => { if(currentPage < totalPages) { currentPage++; renderData(); } };
            container.appendChild(nextBtn);
        }

        // АЖИЛТАН СОНГОЛТ ӨӨРЧЛӨГДӨХӨД ХУУДАСЫГ 1 БОЛГОНО
        function onEmployeeChange() { 
            currentPage = 1; 
            renderData(); 
        }

        // ХАРАГДАХ МӨРИЙН ТОО ӨӨРЧЛӨГДӨХӨД
        function onPerPageChange() {
            rowsPerPage = document.getElementById('per-page-select').value;
            currentPage = 1; // Үргэлж эхний хуудас руу шилжүүлнэ
            renderData();
        }

        // ГҮЙЛГЭЭ ХАДГАЛАХ
        document.getElementById('tx-form').addEventListener('submit', async (e) => {
            e.preventDefault();
            const currentSelectedInMain = document.getElementById('main-employee-select').value;

            const payload = {
                id: document.getElementById('tx-id').value,
                employee_id: document.getElementById('modal-tx-employee').value,
                date: document.getElementById('tx-date').value,
                description: document.getElementById('tx-desc').value,
                type: document.getElementById('tx-type').value,
                amount: document.getElementById('tx-amount').value
            };

            const res = await fetch('api.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify(payload)
            });

            if ((await res.json()).status === 'success') {
                closeModal('add-tx-modal');
                await fetchTransactions();
                document.getElementById('main-employee-select').value = currentSelectedInMain;
                renderData();
            }
        });

        // АЖИЛТАН ХАДГАЛАХ
        document.getElementById('emp-form').addEventListener('submit', async (e) => {
            e.preventDefault();
            const currentSelectedInMain = document.getElementById('main-employee-select').value;
            const payload = {
                id: document.getElementById('emp-id').value,
                name: document.getElementById('emp-name').value,
                position: document.getElementById('emp-position').value
            };
            const res = await fetch('api.php?action=employees', {
                method: 'POST',
                headers: {'Content-Type': 'application/json'},
                body: JSON.stringify(payload)
            });
            if((await res.json()).status === 'success') {
                closeModal('add-employee-modal');
                await fetchEmployees();
                await fetchTransactions();
                document.getElementById('main-employee-select').value = currentSelectedInMain;
                renderData();
            }
        });

        // АЖИЛТАН УСТГАХ
        async function deleteEmployee(id) {
            if(confirm('Ажилтныг устгаснаар түүний бүх гүйлгээ хамт устдаг. Устгах уу?')) {
                const res = await fetch('api.php?action=delete_employee', {
                    method: 'POST',
                    headers: {'Content-Type': 'application/json'},
                    body: JSON.stringify({id: id})
                });
                if((await res.json()).status === 'success') {
                    await fetchEmployees();
                    await fetchTransactions();
                }
            }
        }

        async function init() {
            await fetchEmployees();
            await fetchTransactions();
        }
        init();
    </script>
</body>
</html>
