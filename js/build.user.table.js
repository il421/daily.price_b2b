'use strict';
(function () {
  const DAILYPRICE_DATA = 'server_files/get.users.table.data.php';
  let table = [];

  const tableTemplate = document.querySelector('#table-template');
  const tableTemplateToClone = tableTemplate.content.querySelector('.table-row');
  const tableContainer = document.querySelector('.operate-table__dist');

  const filterRoleAdmin = document.querySelector('#filter-role__admin');
  const filterRoleStaff = document.querySelector('#filter-role__staff');
  const filterRoleBasic = document.querySelector('#filter-role__basic');
  const filterRoleAll = document.querySelector('#filter-role__all');

  window.load(DAILYPRICE_DATA, (evt) => {
    //LOAD DATA
    table = JSON.parse(evt.target.responseText);
    console.log(table);

    //RENDER ELEMENTS
    const renderTable = (arr) => {
      const userId = document.querySelectorAll('.table-row td:nth-child(1)');
      const userEmail = document.querySelectorAll('.table-row td:nth-child(2)');
      const userName = document.querySelectorAll('.table-row td:nth-child(3)');
      const userPhone = document.querySelectorAll('.table-row td:nth-child(4)');
      const userRole = document.querySelectorAll('.table-row td:nth-child(5)');
      const userActive = document.querySelectorAll('.table-row td:nth-child(6) input');
      const btnDelete = document.querySelectorAll('.btn-delete');
      const btnAdd = document.querySelector('.btn-add');
      const btnEdit = document.querySelectorAll('.btn-edit');
      const btnClose = document.querySelector('.btn-close');
      const btnDel = document.querySelectorAll('.btn-delete');

      for (let i = 0; i < arr.length; i++) {
        userId[i].innerHTML = arr[i][0];
        userEmail[i].innerHTML = arr[i][1];
        userName[i].innerHTML = arr[i][2] + ' ' + arr[i][3];
        userPhone[i].innerHTML = arr[i][4];
        userRole[i].innerHTML = arr[i][5];
        btnDelete[i].href = 'server_files/delete.user.php?ID=' + arr[i][0];
        arr[i][6] == 1 ? userActive[i].checked = true : false;
      }

      window.operateForm(btnAdd, btnEdit, btnClose, btnDel);
    };

    //MAKE ROWS
    const makeTable = (arr) => {
      while (tableContainer.lastChild) {
        tableContainer.removeChild(tableContainer.lastChild);
      } //Clean container
      arr.forEach(() => {
        tableContainer.appendChild(tableTemplateToClone.cloneNode(true));
      });
      renderTable(arr); //Render container
    };

    //FILTERS
    const onChangeFilterRole = (btn, arr) => {
      btn.addEventListener('focus', () => {
        makeTable(arr);
      });
    };

    makeTable(table);
    onChangeFilterRole(filterRoleAll, table);
    onChangeFilterRole(filterRoleAdmin, window.utils.filterTableByRole(table, 'admin'));
    onChangeFilterRole(filterRoleStaff, window.utils.filterTableByRole(table, 'staff'));
    onChangeFilterRole(filterRoleBasic, window.utils.filterTableByRole(table, 'basic'));
  });
})()
