document.addEventListener("DOMContentLoaded", () => {
    // Получаем все заголовки таблицы
    const tableHeaders = document.querySelectorAll(
        ".score-table__results-title"
    );
    // Получаем саму таблицу
    const table = document.getElementById("score_results");
    // Получаем элемент для отображения статуса сортировки
    const sortStatus = document.getElementById("sortStatus");
    let currentSortedIndex = null; // Индекс текущей отсортированной колонки
    let currentSortOrder = "desc"; // Текущий порядок сортировки

    // Добавляем класс score-table__value-bold всем td первого столбца по умолчанию
    function addBoldClassToFirstColumn() {
        const rows = table.querySelectorAll("tbody tr"); // Получаем все строки таблицы
        rows.forEach((row) => {
            const firstCell = row.cells[0]; // Получаем первую ячейку
            if (firstCell) {
                firstCell.classList.add("score-table__value-bold"); // Добавляем класс
            }
        });
    }
    addBoldClassToFirstColumn(); // Вызов функции для добавления класса

    // Функция обновления текста сортировки с обёрткой в span.score-table__sort-value
    function updateSortStatus(text) {
        sortStatus.innerHTML = `
        <span class="score-table__sort-title">Текущая сортировка значений:</span> <span class="score-table__sort-value">${text}</span>
        `;
    }

    // Установка начальной сортировки — статус "не задана"
    table.setAttribute("data-order", currentSortOrder);
    updateSortStatus("не задана");

    // Добавляем обработчик на заголовки с классом score-table__results-title
    tableHeaders.forEach((header, index) => {
        header.onclick = function () {
            sortTable(index); // Сортируем таблицу по нажатому заголовку
            highlightColumn(index); // Подсвечиваем отсортированную колонку
            // После сортировки снова добавляем класс, чтобы первая колонка в отсортированных строках тоже была с классом
            addBoldClassToFirstColumn();
        };
    });

    // Функция сортировки таблицы по колонке n
    function sortTable(n) {
        const rows = Array.from(table.rows).slice(1); // Исключаем заголовок

        // Если кликаем по той же колонке — меняем порядок сортировки, иначе по умолчанию desc
        if (currentSortedIndex === n) {
            currentSortOrder = currentSortOrder === "asc" ? "desc" : "asc"; // Меняем порядок
        } else {
            currentSortOrder = "desc"; // По умолчанию убывающая сортировка
        }
        table.setAttribute("data-order", currentSortOrder);

        // Функция для сравнения двух строк при сортировке
        const compareFunction = (a, b) => {
            let aText = a.cells[n].innerText.trim(); // Получаем текст из ячейки а
            let bText = b.cells[n].innerText.trim(); // Получаем текст из ячейки b

            // Для столбцов с числами парсим как числа
            if ([0, 4, 5, 6, 7, 8].includes(n)) {
                const aValue = parseFloat(aText) || 0; // Преобразуем текст в число
                const bValue = parseFloat(bText) || 0; // Преобразуем текст в число
                return currentSortOrder === "asc"
                    ? aValue - bValue // Сравниваем для сортировки по возрастанию
                    : bValue - aValue; // Сравниваем для сортировки по убыванию
            }
            // Для остальных – сортируем строки без учёта регистра
            aText = aText.toLowerCase(); // Приводим к нижнему регистру
            bText = bText.toLowerCase(); // Приводим к нижнему регистру
            if (aText < bText) return currentSortOrder === "asc" ? -1 : 1; // Сравнение строк
            if (aText > bText) return currentSortOrder === "asc" ? 1 : -1; // Сравнение строк
            return 0; // Если равны
        };

        rows.sort(compareFunction); // Сортируем строки

        // Обновляем статус сортировки
        updateSortStatus(
            currentSortOrder === "asc" ? "по возрастанию" : "по убыванию"
        );

        // Обновляем тело таблицы
        const tbody = table.tBodies[0]; // Получаем тело таблицы
        tbody.innerHTML = ""; // Очищаем текущее содержимое
        rows.forEach((row) => tbody.appendChild(row)); // Добавляем отсортированные строки

        currentSortedIndex = n; // Сохраняем индекс текущей сортировки
    }

    // Подсветка колонок при сортировке
    function highlightColumn(n) {
        const rows = table.querySelectorAll("tr"); // Получаем все строки таблицы

        // Убираем подсветку со всех ячеек
        rows.forEach((row) => {
            for (let i = 0; i < row.cells.length; i++) {
                row.cells[i].classList.remove("score-table__highlight"); // Удаляем класс подсветки
            }
        });

        // Подсвечиваем выбранную колонку
        rows.forEach((row) => {
            const cell = row.cells[n]; // Получаем ячейку по индексу
            if (cell) {
                cell.classList.add("score-table__highlight"); // Добавляем класс подсветки
            }
        });
    }
});
