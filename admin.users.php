<?php
  session_start();

  if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] != 'admin') {
    header('Location: server_files/logout.php');
  };
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="ie=edge">
  <link href="css/bootstrap.min.css" rel="stylesheet">
  <link href="css/style.css" rel="stylesheet">
  <title>Admin panel</title>
</head>
<body>
  <header>
    <nav>
      <div class="container">
        <div class="header row align-items-center">
          <div class="col-1 header__logo"><a href="index.php">Daily<br>Prices</a></div>
          <div class="col-11 header__menu">
            <ul>
              <li class="header__menu--active"><a href="">Users</a></li>
              <li><a href="admin.stores.php">Stores</a></li>
              <li><a href="admin.subscription.php">Subscription</a></li>
              <li><a href="server_files/logout.php">Log out</a></li>
            </ul>
          </div>
        </div>
      </div>
    </nav>
  </header>
  <main class="container">
    <div class="operate-table operate-table--users">
      <div class="operate-table__name">
        <?php echo 'Hi, ' . $_SESSION['user_first']; ?>
      </div>
      <div class="btn-group btn-group-toggle filter-role mb-2" data-toggle="buttons">
        <label class="btn btn-secondary active">
          <input type="radio" name="options" id="filter-role__all" autocomplete="off" checked> All
        </label>
        <label class="btn btn-secondary">
          <input type="radio" name="options" id="filter-role__admin" autocomplete="off"> Admin
        </label>
        <label class="btn btn-secondary">
          <input type="radio" name="options" id="filter-role__staff" autocomplete="off"> Staff
        </label>
        <label class="btn btn-secondary">
          <input type="radio" name="options" id="filter-role__basic" autocomplete="off"> Basic
        </label>
      </div>
      <table class="table">
        <thead class="table">
          <tr>
            <th scope="col">ID</th>
            <th scope="col">Email</th>
            <th scope="col">Full name</th>
            <th scope="col">Phone</th>
            <th scope="col">Role</th>
            <th scope="col">Activation</th>
            <th scope="col">
              <button class="btn-add btn btn-dark btn--modificator btn--add" data-toggle="modal" data-target="#exampleModal"></button>
            </th>
          </tr>
        </thead>
        <tbody class="operate-table__dist">

<!-- code must be here -->

        </tbody>
      </table>
    </div>
    <div class="edit-form-users modal fade" data-backdrop="static" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
      <div class="modal-dialog" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <h2 class="edit-form-users__title"></h2>
          </div>
          <form class="edit-form-users__form m-3" action="" method="post">
            <div class="form-row">
              <div class="form-group hidden">
                <input type="text" class="form-control edit-form-users__id" id="user_id"  name="id" placeholder="ID" readonly>
              </div>
              <div class="form-group col md-6">
                <label for="email">User's email</label>
                <input type="email" id="email" class="form-control edit-form-users__email" id="user_email" name="email" pattern="[a-z0-9._%+-]+@[a-z0-9.-]+[a-z]{2,3}$" placeholder="Enter email" required>
              </div>
              <div class="form-group col md-6">
                <label for="pwd">Password</label>
                <input type="text" id="pwd" class="form-control edit-form-users__pwd" id="user_password" name="password" placeholder="Password">
              </div>
            </div>
            <div class="form-row">
              <div class="form-group col md-6">
                <label for="fname">First name</label>
                <input type="text" id="fname" class="form-control edit-form-users__first" id="user_first" name="firstName" placeholder="First name" required>
              </div>
              <div class="form-group col md-6">
                <label for="lname">Last name</label>
                <input type="text" id="lname" class="form-control edit-form-users__last" id="user_last" name="lastName" placeholder="Last name" required>
              </div>
            </div>
            <div class="form-group">
              <label for="phone">Phone number</label>
              <input type="number" id="phone" class="form-control edit-form-users__phone" id="user_phone" name="phone" placeholder="Phone number" required>
            </div>
            <div class="form-group mb-3 system-radio-wrapper">
              <div class="form-check system-radio system-radio--modal">
                <input class="form-check-input" type="radio" name="role" id="user_role1" value="basic" checked>
                <label class="form-check-label" for="user_role1"></label>
                <span>basic</span>
              </div>
              <div class="form-check system-radio system-radio--modal">
                <input class="form-check-input" type="radio" name="role" id="user_role2" value="staff">
                <label class="form-check-label" for="user_role2"></label>
                <span>staff</span>
              </div>
              <div class="form-check system-radio system-radio--modal">
                <input class="form-check-input" type="radio" name="role" id="user_role3" value="admin">
                <label class="form-check-label" for="user_role3"></label>
                <span>admin</span>
              </div>
            </div>
            <div class="form-check__wrapper">
              <div class="form-check system-checkbox system-checkbox--modal">
                <input type="hidden" name="active" value="0">
                <input type="checkbox" class="form-check-input edit-form-users__active" id="user_active" name="active" value="1">
                <label class="form-check-label" for="user_active"></label>
              </div>
              <span>activation</span>
            </div>
            <button type="button" class="btn-close btn btn-dark btn--modificator btn--close" data-dismiss="modal"></button>
            <button type="submit" class="btn-submit btn btn-dark btn--modificator"></button>
          </form>
        </div>
      </div>
    </div>
    <template id="table-template" class="hidden">
      <tr class="table-row">
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td>
          <div class="operate-table__checkbox system-checkbox">
            <input class="form-check-input" id="active" type="checkbox" name="active" disabled>
            <label class="form-check-label" for="active"></label>
          </div>
        </td>
        <td class="operate-table__btns">
          <a class="btn-edit btn btn-dark btn--modificator btn--edit" data-toggle="modal" data-target="#exampleModal" role="button"></a>
          <a class="btn-delete btn btn-dark btn--modificator btn--delete" href="" role="button"></a>
        </td>
      </tr>
    </template>
  </main>
  <script src="js/jquery.min.js"></script>
  <script src="js/bootstrap.min.js"></script>
  <script src="js/load.js"></script>
  <script src="js/build.user.table.js"></script>
  <script src="js/utilites.js"></script>
  <script src="js/load.users.js"></script>

</body>
</html>
