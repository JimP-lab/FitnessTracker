## Meta Graph API Integration Guide

This guide walks you through setting up Meta Graph API integration to automate your social media posts. Follow each step carefully, and add screenshots as you complete them.

---

## Step 1: Creating Your App in Meta Business Manager

### What You'll Do
Create a new app in Meta Developers that will connect to your Facebook page and allow you to post automatically.

### Step-by-Step Instructions

**1.1 Navigate to Meta Developers**

- Open your browser and go to [Meta Developers](https://developers.facebook.com/)
- Click **"My Apps"** in the top right corner of the page
- If you're not logged in, log in with your Facebook account
- Once logged in, look for the **"Create App"** button and click it

*At this point, you should see the Meta Developers dashboard. The Create App button is typically in the top right area next to your profile icon.*

### Screenshot: Meta Developers Homepage

![Meta Developers - Create App Button](./images/01-meta-developers-homepage.png)

*Figure 1: Click the "Create App" button to start creating your application*

---

**1.2 Fill in the App Information**

When you click "Create App," a form appears asking for details about your application. Fill in the following:

- **App Name:** Enter a descriptive name like `FitnessTracker-AutoPoster` or `AutomationSystem-Media`
  - This name helps you identify the app later
  - Use something clear that relates to your project

- **App Contact Email:** Enter your email address
  - This is where Meta will send notifications about your app
  - Use the email associated with your Facebook account

- **App Purpose:** Select **"Business"** from the dropdown
  - Business apps can manage pages and post content
  - This is required for automation features

- **App Type:** Keep it as **"Business"** (usually the default)
  - This allows you to access page management and posting features

After filling in all fields, click the **"Create App"** button at the bottom of the form.

### Screenshot: App Creation Form

![App Creation Form](./images/02-create-app-form.png)

*Figure 2: Fill in the app details. Make sure to select "Business" for both App Purpose and App Type*

### Important Information to Note

When you create the app, Meta will display your **App ID** and **App Secret**. 

- **App ID:** A unique identifier for your app (example: `123456789012345`)
- **App Secret:** A secret code to authenticate your app (keep this private and never share it)

Save these values somewhere safe; you'll need them later.

---

**1.3 Verify Your App Was Created Successfully**

After clicking "Create App," you should be redirected to your app dashboard. 

**How to know if it worked:**
- ✅ You see a page titled "Dashboard" with your app name
- ✅ There's a section showing your **App ID**
- ✅ No error messages appear on the screen
- ✅ The page displays options like "Settings," "Products," and "Tools"

If you see these elements, your app has been created successfully!

### Screenshot: App Dashboard

![App Dashboard](./images/03-app-created-dashboard.png)

*Figure 3: Your app dashboard after successful creation. Note the App ID highlighted in the top left*

---

## Step 2: Adding Facebook Login Product to Your App

### What You'll Do
Add the "Facebook Login" product to your app so it can authenticate and access your page.

### Step-by-Step Instructions

**2.1 Go to Products Section**

From your app dashboard:
- Look for the **"Products"** section on the left sidebar
- Or click **"Add Product"** if you see that button in the main area
- Search for **"Facebook Login"** in the available products list

Facebook Login is the product that allows your app to authenticate and get permission to manage your page.

---

**2.2 Set Up Facebook Login**

When you find Facebook Login:
- Click on it or click **"Set Up"**
- Select your platform: Choose **"Web"** (since you'll be using this on a web server)
- Complete the setup wizard by clicking "Next" or "Continue" through each screen

The wizard will guide you through configuration steps. Follow each prompt.

### Screenshot: Facebook Login Setup

![Facebook Login Setup](./images/04-facebook-login-setup.png)

*Figure 4: Select "Web" as your platform when setting up Facebook Login*

---

**2.3 Configure Login Settings**

After completing the setup wizard:
- Go to **Settings → Basic** in the left menu
- Add your website URL where indicated (this is where your automation will run)
- Save any changes

This tells Meta which websites are authorized to use your app for posting.

---

## Step 3: Getting Your Facebook Page ID

### What You'll Do
Find the unique ID number of your Facebook page. This ID is required to tell Meta which page to post to.

### Step-by-Step Instructions

**3.1 Find Your Page ID Through Business Manager**

- Open [Meta Business Manager](https://business.facebook.com/)
- Log in with your Facebook account
- On the left sidebar, click **"Accounts"** or **"Pages"**
- Look for your page in the list and click on it

Your page will have information displayed, including a section labeled **"Page ID"** or **"ID"**.

**Note:** The Page ID is a long number, typically 15 digits. Example: `123456789012345`

---

**3.2 Alternative: Find Page ID from Your Page URL**

If you can't find it in Business Manager:

1. Go to your Facebook page directly (facebook.com/yourpage)
2. Click **"About"** on the page
3. Scroll down to find the **"Page ID"** section
4. You'll see your ID displayed there

Copy this ID and save it in a safe place.

### Screenshot: Finding Page ID in Business Manager

![Page ID in Business Manager](./images/05-business-manager-page-id.png)

*Figure 5: Navigate to your page in Business Manager and locate the Page ID*

---

**3.3 Verify Your Page ID**

Your Page ID should be:
- ✅ A number (not text or letters mixed)
- ✅ Around 15 digits long
- ✅ Unique to your specific page
- ✅ Visible in Business Manager under page settings

Once you have this, save it. You'll use it in the next steps.

---

## Step 4: Generating Your Access Token

### What You'll Do
Create an Access Token, which is like a password that allows your app to post to your page. This token proves your app has permission to access your page.

### Step-by-Step Instructions

**4.1 Use Graph API Explorer (Quick Method)**

This is the easiest way to test and get your first token.

- Go to [Graph API Explorer](https://developers.facebook.com/tools/explorer/)
- In the top left, select your app from the dropdown menu
- Under **"User or Page"**, select **"Get User Access Token"**

A dialog box will appear asking for permissions.

---

**4.2 Grant Required Permissions**

When the permissions dialog opens, look for and check these permissions:

- ✅ `pages_manage_posts` - Allows posting to your page
- ✅ `pages_read_engagement` - Allows reading page insights
- ✅ `pages_read_user_content` - Allows reading page content

**Important:** These permissions let your app post to your page. Only grant permissions you actually need.

After selecting permissions, click **"Generate Token"** or **"Continue"**.

### Screenshot: Graph API Explorer Permissions

![Graph API Explorer Permissions](./images/06-graph-api-explorer-permissions.png)

*Figure 6: Select the required permissions before generating your token*

---

**4.3 Copy Your Access Token**

After generating the token:
- A long string of characters appears in the "Access Token" field
- This is your **Access Token**
- Click on the field and select all the text, then copy it
- **NEVER share this token with anyone** - treat it like a password

Example token format: `EAA...` (very long string)

### Screenshot: Copying Your Access Token

![Copy Access Token](./images/07-copy-access-token.png)

*Figure 7: Copy the Access Token carefully. This grants access to your page*

---

**4.4 Get a Long-Lived Access Token (For Production)**

The token you just generated expires in about 2 hours. For automation that runs continuously, you need a **Long-Lived Token** that lasts ~60 days.

In Graph API Explorer:
- Click on the **Access Token** field
- A dialog appears with token details
- Look for an option to **"Extend Token"** or similar
- Click it to get a long-lived version

### Screenshot: Extending Your Token

![Extend Access Token](./images/08-extend-access-token.png)

*Figure 8: Extend your token to get a long-lived version for production use*

---

**4.5 Get Your Page Access Token**

Now you need a **Page Access Token**, which is different from your User Access Token. The page token is more secure for production.

In Graph API Explorer:
- Run this query: `GET /me/accounts?fields=access_token,name,id`
- Click the blue play button to execute
- Results will show all pages you manage
- Find your page in the list
- Copy the `access_token` value shown for your page

This is your **Page Access Token**. It never expires (unlike user tokens), making it ideal for automation.

### Screenshot: Getting Page Access Token

![Page Access Token](./images/09-page-access-token.png)

*Figure 9: Query your pages to get your Page Access Token. This token doesn't expire*

---

**Important Token Information:**

| Token Type | Duration | Use Case |
|-----------|----------|----------|
| User Access Token (Short) | ~2 hours | Testing, development |
| User Access Token (Long) | ~60 days | Temporary automation |
| Page Access Token | Never expires | Production automation |

For your automation system, use the **Page Access Token** because it's long-lasting and secure.

---

## Step 5: Storing Your Credentials Securely

### What You'll Do
Store your credentials (Page ID, Access Token, App ID) in a way that keeps them safe but accessible to your code.

### Step-by-Step Instructions

**5.1 Create a Configuration File (Environment Variables Method - Recommended)**

Instead of storing credentials in your code, use environment variables:

1. Create a file called `.env` in your project root directory
2. Add the following lines:

```
META_PAGE_ID=123456789012345
META_ACCESS_TOKEN=EAAY...your-token-here...
META_APP_ID=your-app-id-here
META_APP_SECRET=your-app-secret-here
```

Replace the values with your actual credentials:
- `123456789012345` = Your Page ID
- `EAAY...` = Your Page Access Token
- Other values = From your app settings

3. Save the file

**Important:** Add `.env` to your `.gitignore` file so it never gets uploaded to GitHub!

Example `.gitignore` entry:
```
.env
.env.local
config.php
```

---

**5.2 Why This Matters**

- ✅ **Secure:** Credentials are not visible in your code
- ✅ **Flexible:** Easy to change tokens without editing code
- ✅ **Professional:** Industry standard practice
- ✅ **Protected:** If you upload to GitHub, tokens stay private

---

**5.3 Using Credentials in Your Code**

**In PHP:**
```php
<?php
// Load environment variables from .env file
require 'vendor/autoload.php'; // if using composer
$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->load();

$page_id = $_ENV['META_PAGE_ID'];
$access_token = $_ENV['META_ACCESS_TOKEN'];
$app_id = $_ENV['META_APP_ID'];
```

**In JavaScript/Node.js:**
```javascript
// Using dotenv package
require('dotenv').config();

const pageId = process.env.META_PAGE_ID;
const accessToken = process.env.META_ACCESS_TOKEN;
const appId = process.env.META_APP_ID;
```

---

**5.4 Checkpoint: Verify Your Setup**

Before moving to the next step, confirm:
- [ ] You have your Page ID saved
- [ ] You have your Page Access Token saved
- [ ] You have your App ID saved
- [ ] You created a `.env` file with these values
- [ ] `.env` is added to `.gitignore`

---

## Step 6: Testing Your API Integration

### What You'll Do
Test that your credentials work by creating a test post using the Meta Graph API.

### Step-by-Step Instructions

**6.1 Use Graph API Explorer for Quick Testing**

1. Go back to [Graph API Explorer](https://developers.facebook.com/tools/explorer/)
2. In the top left, select your app
3. In the query field at the top, enter:

```
PAGE_ID/feed
```

Replace `PAGE_ID` with your actual page ID. Example: `123456789012345/feed`

4. Change the method from **GET** to **POST** (dropdown menu)
5. Below the query field, click **"+ Add a Field"** or look for "Params"
6. Add this parameter:
   - **Name:** `message`
   - **Value:** `Test post from Meta Graph API`

7. Click the blue play button to send the request

### Screenshot: Testing in Graph API Explorer

![Testing API](./images/10-testing-api-explorer.png)

*Figure 10: Test your API by posting a message through Graph API Explorer*

---

**6.2 Check the Response**

After clicking the play button:

**Success Response:**
```json
{
  "id": "123456789012345_987654321012345"
}
```
- ✅ This ID confirms your post was created
- ✅ Check your Facebook page - you should see the test post

**Error Response:**
```json
{
  "error": {
    "message": "Invalid OAuth Token",
    "code": 190
  }
}
```
- ❌ Your token has expired or is invalid
- ❌ Generate a new token and try again

---

**6.3 Verify on Your Facebook Page**

1. Open your Facebook page in a new tab
2. Look at your timeline/feed
3. You should see your test post: **"Test post from Meta Graph API"**
4. If you see it, your API integration works! ✅

If you don't see it:
- Check that you used the correct Page ID
- Verify your access token is valid
- Make sure the token includes `pages_manage_posts` permission

---

**6.4 Delete Your Test Post (Optional)**

To clean up, you can delete the test post:

1. In Graph API Explorer, change method back to **GET**
2. Query: `POST_ID?fields=id` (where POST_ID is the ID from the response)
3. Change to **DELETE**
4. Click the play button
5. The test post will be deleted

---

## Step 7: Setting Up Your Automation

### What You'll Do
Configure your automation system to post automatically at scheduled times using your credentials.

### Step-by-Step Instructions

**7.1 Create Your Posting Script**

You can use PHP, JavaScript, Python, or any language that makes HTTP requests. Here's an example:

**PHP Example:**

```php
<?php
// Load your credentials from .env
require 'vendor/autoload.php';
$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->load();

$page_id = $_ENV['META_PAGE_ID'];
$access_token = $_ENV['META_ACCESS_TOKEN'];
$api_version = 'v18.0';

// Create the post
$message = "Check out our Fitness Tracker App!";
$link = "https://www.example.com";
$picture = "https://example.com/image.jpg";

// Build the API URL
$url = "https://graph.facebook.com/{$api_version}/{$page_id}/feed";

// Prepare the data
$data = [
    'message' => $message,
    'link' => $link,
    'picture' => $picture,
    'access_token' => $access_token
];

// Make the request using cURL
$curl = curl_init();
curl_setopt_array($curl, [
    CURLOPT_URL => $url,
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_POST => true,
    CURLOPT_POSTFIELDS => http_build_query($data)
]);

$response = curl_exec($curl);
$result = json_decode($response, true);
curl_close($curl);

// Check if post was successful
if (isset($result['id'])) {
    echo "✅ Post created! ID: " . $result['id'];
} else {
    echo "❌ Error: " . $result['error']['message'];
}
?>
```

---

**7.2 Schedule Your Posts (Optional)**

To post automatically at specific times, use a scheduler:

**Linux/Unix Cron Job:**
```bash
# Post every hour
0 * * * * php /path/to/your/post-script.php

# Post every day at 9 AM
0 9 * * * php /path/to/your/post-script.php
```

**Windows Task Scheduler:**
- Open "Task Scheduler"
- Create new task
- Set trigger (daily, hourly, etc.)
- Set action to run your PHP/JavaScript file
- Save and enable

**Using Zapier (Recommended for beginners):**
- Connect your automation to Zapier
- Set up triggers (time-based)
- Zapier will call your posting script automatically

---

**7.3 Monitor Your Posts**

Once automation is running:
- Check your Facebook page regularly for new posts
- Review engagement (likes, comments, shares)
- If posts stop appearing, check:
  - Your token hasn't expired
  - Your server is running
  - No error messages in your logs

---

## Troubleshooting Common Issues

### Issue: "Invalid OAuth Token" Error

**Cause:** Your access token expired or is incorrect

**Solution:**
1. Generate a new Page Access Token (Step 4)
2. Update your `.env` file with the new token
3. Test again with Graph API Explorer

---

### Issue: "Permissions Error" (Error 200)

**Cause:** Your token doesn't have required permissions

**Solution:**
1. Make sure your token includes `pages_manage_posts`
2. Generate a new token with all required permissions
3. Try testing again

---

### Issue: "The Action Blocked" (Error 368)

**Cause:** You're posting too frequently (rate limiting)

**Solution:**
1. Wait a few minutes before posting again
2. Reduce posting frequency in your automation
3. Implement exponential backoff in your code

---

### Issue: Post Not Appearing on Facebook Page

**Cause:** Post was created (you got an ID) but not visible

**Possible Solutions:**
1. Refresh your Facebook page
2. Check page moderation queue
3. Verify you have admin access to the page
4. Make sure the page ID is correct

---

## Summary Checklist

Before you start automating, confirm:

- [ ] App created in Meta Developers
- [ ] Facebook Login product added
- [ ] Page ID obtained
- [ ] Page Access Token generated
- [ ] Long-lived token created
- [ ] Credentials stored in `.env`
- [ ] Test post successful
- [ ] Post visible on Facebook page
- [ ] Automation script created
- [ ] Scheduler configured (if needed)

---

## Resources

- [Meta Graph API Documentation](https://developers.facebook.com/docs/graph-api)
- [Access Token Documentation](https://developers.facebook.com/docs/facebook-login/access-tokens)
- [Page Feed API Reference](https://developers.facebook.com/docs/graph-api/reference/page/feed)
- [Error Codes Reference](https://developers.facebook.com/docs/graph-api/using-graph-api/error-handling)
- [Graph API Explorer Tool](https://developers.facebook.com/tools/explorer/)

---

## Additional Screenshots

*Add more screenshots as you complete each step and gather them:*

### Facebook Page - Test Post
![Test Post on Page](./images/11-test-post-on-page.png)

*Figure 11: Your test post should appear on your Facebook page timeline*

### Automation System Running
![Automation Dashboard](./images/12-automation-running.png)

*Figure 12: Monitor your automation posts in real-time*

---

## Need Help?

If you get stuck:
1. Re-read the step carefully
2. Check the screenshots to see what it should look like
3. Review the troubleshooting section
4. Check Meta's official documentation
5. Test small parts first before creating the full automation

Happy automating! 🚀
