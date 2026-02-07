window.onload = () => {
    setupFilters();
}

function setupFilters() {
    const table = document.querySelector("section.report table");

    const headers = table.querySelectorAll("th");

    headers.forEach((th, index) => {
        const par = document.createElement("p");
        const selectionBox = document.createElement("select");
        par.appendChild(selectionBox);
        th.appendChild(par);
        const caption = th.querySelector("p.caption");
        caption.addEventListener("click", (event) => {
           sortTable(table, index);
        });

        setupOneFilter(table, selectionBox, index);

        selectionBox.addEventListener("change", (event) => {
            const selectedValue = event.target.value;
            const values = table.querySelectorAll(`tr td:nth-child(${index+1})`);
            values.forEach(td => td.classList.remove('filtered'));

            // now set a new filter only if a real value was added
            if (selectedValue !== "-1") {
                const filteredItems = Array.from(values).filter(td => td.textContent !== selectedValue);
                filteredItems.forEach(td => td.classList.add('filtered'));
                selectionBox.classList.add("has-selection");
            }
            else {
                selectionBox.classList.remove("has-selection");
            }
        });
    });
}

function setupOneFilter(table, selectionBox, index) {
    selectionBox.innerHTML = ""; // clear old stuff
    selectionBox.classList.add("selection-container");
    const emptyOption = document.createElement("option");
    emptyOption.value = "-1";
    emptyOption.textContent = "<empty>";
    selectionBox.appendChild(emptyOption);

    const uniqueValues = new Set();
    const values = table.querySelectorAll(`tr td:nth-child(${index+1}):not(.filtered) `);
    values.forEach(td => uniqueValues.add(td.textContent));

    const sortedUniqueValues =  Array.from(uniqueValues).sort((a,b) => a.localeCompare(b));

    sortedUniqueValues.forEach(val => {
        const option = document.createElement("option");
        option.value = val;
        option.textContent = val;
        selectionBox.appendChild(option);
    });

}

function sortTable(table, colIndex) {
    const tbody = table.querySelector("tbody");
    const rows = Array.from(tbody.querySelectorAll("tr"));
    const headers = table.querySelectorAll("th");

    // Haal de huidige sorteervolgorde op
    const currentOrder = headers[colIndex].getAttribute("data-order");
    const isAscending = currentOrder !== "asc";

    // Reset alle th's
    headers.forEach(th => th.removeAttribute("data-order"));

    // Zet de nieuwe sorteervolgorde op de aangeklikte th
    headers[colIndex].setAttribute("data-order", isAscending ? "asc" : "desc");

    rows.sort((rowA, rowB) => {
        let cellA = rowA.cells[colIndex].textContent.trim();
        let cellB = rowB.cells[colIndex].textContent.trim();
        if (cellA === "") cellA = isAscending ? "zzz" : "";
        if (cellB === "") cellB = isAscending ? "zzz" : "";

        let valueA = isNaN(cellA) ? cellA.toLowerCase() : Number(cellA);
        let valueB = isNaN(cellB) ? cellB.toLowerCase() : Number(cellB);

        return isAscending ? (valueA > valueB ? 1 : -1) : (valueA < valueB ? 1 : -1);
    });

    tbody.innerHTML = "";
    rows.forEach(row => tbody.appendChild(row));
}
