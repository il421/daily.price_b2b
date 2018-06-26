# daily.price_b2b

*It is a part of a group project aimed at providing a website & service to connect buyers (students, deal seekers, thrifty kiwis) and sellers based on their searches and location. Providing buyers with deals and therefore savings, and on the other hand sellers (mostly small to medium sized businesses) a way to move their goods and reduce wastage.*

My part was the admin part, and a some kind of an inventory system, with a functionality of pushing special deals to the web site.

To run the system you will have to use a server (XAMPP or something else), mySQL (the database in the repository as well - **dailyprices_newest.sql**).

In the **index.php** page you need to change sessions to look at all interfaces (the login part was not my part).
It can be admin or staff.

**$_SESSION['user_role'] = 'admin'**
