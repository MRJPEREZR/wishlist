# WEBAPP Final project

# Group members

Team 1: use case 1 - Gift wishlist creation
WANG Lixi
ZHANG Chen

Team 2: use case 2 - Purchasing a gift for someone from that person’s wishlist
XIANG Yuntian
ZHU Xinlei

Team 3: use case 3 - Administration
MIRANDA RODRIGUEZ Anamaria
PEREZ RAMIREZ Julian

# How to execute

```
cd server/
docker compose up
```

## Use-cases

### 1 - Gift wishlist creation
- A registration page for registering as a new user, as well as for logging in.
- A page listing your wishlists, with buttons for:
∗ creating a new wishlist (with a name and a deadline for receiving gifts),
    - accepting/refusing an invitation to a wishlist,
    - editing an existing wishlist,
    - deleting an existing wishlist, and
    - getting the URL for sharing a wishlist with another registered user (for joint creation of the wishlist),
    - getting the URL for displaying the wishlist to everyone (for allowing users to purchase you gifts from this wishlist).
- A page for adding/editing/deleting an item to your wishlist including:
    - item title,
    - textual description of the purpose of this item,
    - URL to a webpage where this item can be bought.

### 2 - Purchasing a gift for someone from that person’s wishlist

- A page for viewing a person’s wishlist using its sharing URL, with the following sorting
buttons:
    - sort items by price in ascending order;
    - sort items by price in descending order.
Each item should have a purchase button, which leads to the webpage where this item can be bought. Upon clicking this button, two things happen: (1) a webpage is opened where that item can be bought, and (2) a webpage is opened requesting you to upload a purchase proof (see below).
When pointing the cursor on an item already purchased by someone, the name of the person who
purchased it will appear, together with the accompanying congratulatory message.

- A webpage for proving the purchase of an item On this webpage you are requested to upload
a purchase proof printscreen for a given item, accompanied by some user-input congratulatory
text. Once this purchase proof is uploaded, the item gets grayed out in the wishlist, indicating
that it was already bought by someone. The congratulatory text for this item can be later edited
by the user who purchased the item.

### 3 - Administration
Use-case 3: Administration
- A dashboard, containing:
    - (a) the sorted list of the top-3 most expensive items bought for a wishlist.
    - (b) the sorted list of the top-3 wishlists by total value of purchased gifts.
Functionalities covered: READ data
- A page allowing to view the list of users, as well as lock/unlock/remove their accounts.