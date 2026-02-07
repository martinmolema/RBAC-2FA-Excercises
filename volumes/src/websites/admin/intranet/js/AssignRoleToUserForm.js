window.onload = () => {
    setup();
}

function setup() {
    const elSelectRole = document.querySelector("select[name='role']");
    elSelectRole.addEventListener("change", (event) => {
        const idRole = event.target.value;
        getSelectedDNMembers(idRole);
    });

    getSelectedDNMembers(elSelectRole.value);
}

function getSelectedDNMembers(value) {
    const dn = encodeURIComponent(value);

    const xhr = new XMLHttpRequest();
    xhr.open("get", `/intranet/ajax/getLdapMembersForGroup.php?dn=${dn}`);

    xhr.onload = (res) => {
        if (xhr.status === 200) {
            const data = JSON.parse(xhr.responseText);
            processData(data);
        }
    }
    xhr.send();
}

function processData(data) {
    const tableBody = document.querySelector("#current-user-list table tbody");
    tableBody.innerHTML = '';
    for (let i = 0; i < data.length; i++) {
        tableBody.appendChild(createOneUser(data[i]));
    }

    const userSelectList = document.querySelector("#user");

    for (let i = 0; i < userSelectList.options.length; i++) {

        const option = userSelectList.options[i];
        const itemAlreadyInList = data.find(u => u['dn'] === option.value);

        option.disabled = (itemAlreadyInList !== undefined);
    }

    const revokeButtons = document.querySelectorAll('button.revoke');
    revokeButtons.forEach(btn => btn.addEventListener('click', (event) => {
        revokePermission(event)
    }));
}

function createOneUser(user) {
    const item = document.createElement("tr");
    item.classList.add("user");

    const td1 = document.createElement("td");
    const td2 = document.createElement("td");
    const td3 = document.createElement("td");

    item.appendChild(td1);
    item.appendChild(td2);
    item.appendChild(td3);

    td1.classList.add('sn');
    td2.classList.add('givenName');

    td1.textContent = user['sn'];
    td2.textContent = user['givenName'];

    const btnDelete = document.createElement('button');
    btnDelete.dataset['dn'] = user['dn'];
    btnDelete.classList.add('revoke');
    btnDelete.textContent = "Revoke";
    td3.appendChild(btnDelete);

    return item;
}

function revokePermission(event) {
    const target = event.target;
    const dnUser = target.dataset['dn'];
    const listOfRoles = document.querySelector('SELECT#role');
    const dnRole = listOfRoles.value;

    const xhr = new XMLHttpRequest();
    xhr.open("post", `/intranet/ajax/revokeUserFromRole.php`);

    const body = new FormData();
    body.set('user', dnUser);
    body.set('role', dnRole);

    xhr.onload = (res) => {
        if (xhr.status === 200) {
            const data = JSON.parse(xhr.responseText);
            processData(data);
        }
        else {
            alert('Fout bij verwijderen rol. ')
        }
    }
    xhr.send(body);

}