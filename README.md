# tuffy-bay
make sure to import "tuffy-bay.sql" file before using website locally

TODO: 
1. form input restrictions (javascript) 
	-ex. username length 8-12, cannot leave "item price" blank before adding an item
2. allow the user to add item to cart without logging in (then make them login if they try to buy it)
3. searching algorithm needs to be improved (not just exact keyword searching), tuffy_inventory->search_item() in functions.php
4. user_page.php will be made into a public profile page (not for editing information)
5. certain things can be done through javascript rather than php, like the admin_add_items.php page
6. there's currently no error display if an admin adds a new item and the name is already taken (SQL is setup to not allow it)
7. right now after a return has been approved, it does not notify the user or give any info (give them a notification)
8. automatic capitalization is happening in account.php
9. Need to organize pages


admin account: 
username: admin_user
password: admin_user