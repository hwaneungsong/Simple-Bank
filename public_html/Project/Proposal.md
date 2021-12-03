# Project Name: Simple Bank
## Project Summary: This project will create a bank simulation for users. They’ll be able to have various accounts, do standard bank functions like deposit, withdraw, internal (user’s accounts)/external(other user’s accounts) transfers, and creating/closing accounts.
## Github Link: (Prod Branch of Project Folder)
## Project Board Link: 
## Website Link: (Heroku Prod of Project folder)
## Your Name:

<!--
### Line item / Feature template (use this for each bullet point)
#### Don't delete this

- [ ] \(mm/dd/yyyy of completion) Feature Title (from the proposal bullet point, if it's a sub-point indent it properly)
  -  List of Evidence of Feature Completion
    - Status: Pending (Completed, Partially working, Incomplete, Pending)
    - Direct Link: (Direct link to the file or files in heroku prod for quick testing (even if it's a protected page))
    - Pull Requests
      - PR link #1 (repeat as necessary)
    - Screenshots
      - Screenshot #1 (paste the image so it uploads to github) (repeat as necessary)
        - Screenshot #1 description explaining what you're trying to show
### End Line item / Feature Template
--> 
### Proposal Checklist and Evidence

- Milestone 1
  - [x] \(10/07/2021) User will be able to register a new account
    -  List of Evidence of Feature Completion
      - Status: Completed
      - Direct Link: https://hs723-prod.herokuapp.com/Project/register.php
      - Pull Requests
        - PR link #1 https://github.com/hwaneungsong/IT202-011/pull/3
      - Screenshots
        - Screenshot #1 ![210F2B54-ACF8-4732-B37E-F3549FB65BC5](https://user-images.githubusercontent.com/90282169/141597867-027518ef-4b46-4f1a-b48c-ab0a71238145.jpeg)
          - Screenshot #1 Showing email validation
        - Screenshot #2 ![7931B839-012A-4AEF-A588-D0D15359CF5F_4_5005_c](https://user-images.githubusercontent.com/90282169/141597999-f745812a-5ba5-4598-9332-eeb999ee0c34.jpeg)
          - Screenshot #2 Showing password column is 60 characters and hashed
        - Screenshot #3 ![5A306A50-B77A-42A0-8B8C-85CF4934D68B](https://user-images.githubusercontent.com/90282169/141598234-2231bfd2-f70e-4cf4-af3f-9b43cebca165.jpeg)
          - Screenshot #3 Showing duplicate email notice
        - Screenshot #4 ![3FFDB6AA-E68F-49A6-A1C9-C06F3B75FE0E](https://user-images.githubusercontent.com/90282169/141598345-8238461b-aa74-4269-a25d-e9f8fc43c0e6.jpeg)
          - Screenshot #4 Showing duplicate username notice
        - Screenshot #5 ![790CF55F-1415-46C8-B9B5-4696EEF04B05](https://user-images.githubusercontent.com/90282169/141598560-a73910de-b94f-4695-b5e8-6d8bbe8b610b.jpeg)
          - Screenshot #5 Showing the code that does server side validation
  - [x] \(10/07/2021) User will be able to login to their account (given they enter the correct credentials)
    -  List of Evidence of Feature Completion
      - Status: Completed
      - Direct Link: https://hs723-prod.herokuapp.com/Project/login.php
      - Pull Requests
        - PR link #1 https://github.com/hwaneungsong/IT202-011/pull/3
      - Screenshots
        - Screenshot #1 ![1022B1A1-1BC6-4A40-995E-681E332DC674_4_5005_c](https://user-images.githubusercontent.com/90282169/141598911-b3a2cf8d-617c-4c39-8f2c-ba8bc32b81ba.jpeg)
          - Screenshot #1 Showing user can login with email
        - Screenshot #2 ![FAD50069-E1C2-457D-A5C8-683F8E944F3E_4_5005_c](https://user-images.githubusercontent.com/90282169/141599221-9cf58f13-40db-4987-939c-bef2229e4152.jpeg)
          - Screenshot #2 Showing login success(for email and username)
        - Screenshot #3 ![461CB50B-B877-433B-A35A-5806EDDF5D89](https://user-images.githubusercontent.com/90282169/141599316-57f99102-1982-4065-8027-397bf04c19ab.jpeg)
          - Screenshot #3 Showing user can login with username
        - Screenshot #4 ![C4D9FD78-6F92-49A5-9530-C3206633C880](https://user-images.githubusercontent.com/90282169/141600586-87deb3de-e3b8-483e-994c-6d7c63af768e.jpeg)
          - Screenshot #4 Showing password is required
        - Screenshot #5 ![4633CE34-2467-40EC-8629-29DD916D322C](https://user-images.githubusercontent.com/90282169/141600676-8398edaa-3071-43b8-b2fb-3da5989852ad.jpeg)
          - Screenshot #5 Logging in should fetch the user's details and save them into the session
        - Screenshot #6 ![FAD50069-E1C2-457D-A5C8-683F8E944F3E_4_5005_c](https://user-images.githubusercontent.com/90282169/141600714-da6e0ff1-040d-4376-87d3-fa9603841f89.jpeg)
          - Screenshot #6 User will be directed to a landing page upon login
  - [x] \(10/07/2021) User will be able to logout
    -  List of Evidence of Feature Completion
      - Status: Complete
      - Direct Link: https://hs723-prod.herokuapp.com/Project/logout.php
      - Pull Requests
        - PR link #1 https://github.com/hwaneungsong/IT202-011/pull/3
      - Screenshots
        - Screenshot #1 ![C339251D-15F4-4A41-BBC4-66F592E66B50_4_5005_c](https://user-images.githubusercontent.com/90282169/141602075-b28d9ff3-76cb-4b04-b057-56165ada3c31.jpeg)
          - Screenshot #1 User is able to logout
        - Screenshot #2 ![952CB2EF-D64A-4E9E-8098-0BCB51DDC457_4_5005_c](https://user-images.githubusercontent.com/90282169/141602113-ee8d14f4-0879-497c-922a-a598137ceef3.jpeg)
          - Screenshot #2 User is redirected to login page
        - Screenshot #3 ![00981689-ECB3-494B-A017-EF8FDDAC7527](https://user-images.githubusercontent.com/90282169/141602755-03072bdf-0a9f-40a6-8361-4f49021f9091.jpeg)
          - Screenshot #3 Session is destroyed so the back button cannot be used
  - [x] \(10/07/2021) Basic security rules implemented
    -  List of Evidence of Feature Completion
      - Status: Complete
      - Direct Link: https://hs723-prod.herokuapp.com/lib/functions.php
      - Pull Requests
        - PR link #1 https://github.com/hwaneungsong/IT202-011/pull/3
      - Screenshots
        - Screenshot #1 ![9A4C3431-B464-441D-A542-4E67BCFEFEDF](https://user-images.githubusercontent.com/90282169/141603330-b4673f47-985a-4d41-8132-959d591114de.jpeg)
          - Screenshot #1 Function to check if user is logged in
        - Screenshot #2 ![9A4C3431-B464-441D-A542-4E67BCFEFEDF](https://user-images.githubusercontent.com/90282169/141603330-b4673f47-985a-4d41-8132-959d591114de.jpeg)
          - Screenshot #2 Function should be called on appropriate pafes that only allow logged in users
        - Screenshot #3 ![9A4C3431-B464-441D-A542-4E67BCFEFEDF](https://user-images.githubusercontent.com/90282169/141603330-b4673f47-985a-4d41-8132-959d591114de.jpeg)
          - Screenshot #3 Have a roles table
  - [x] \(10/28/2021) Basic Roles implemented
    -  List of Evidence of Feature Completion
      - Status: Completed
      - Direct Link: https://hs723-prod.herokuapp.com/Project/admin/create_role.php
      - Pull Requests
        - PR link #1 https://github.com/hwaneungsong/IT202-011/pull/20
      - Screenshots
        - Screenshot #1 ![BC77A5C0-852C-4578-9C76-2B009493165D_4_5005_c](https://user-images.githubusercontent.com/90282169/141603600-08383149-16e5-4e80-8ef9-5b9996ea2beb.jpeg)
          - Screenshot #1 Have a Roles table
        - Screenshot #2 ![56487A4C-B795-4FA2-A635-D28BD6ED1090_4_5005_c](https://user-images.githubusercontent.com/90282169/141603608-5f5e4cce-06b5-48df-b196-e6cfcbc0116a.jpeg)
          - Screenshot #2 Have a User Roles table
        - Screenshot #3 ![48C846E7-A6F9-47F2-98E3-2FA3ACAC7032](https://user-images.githubusercontent.com/90282169/141603899-43410e0b-0872-4f4c-9343-06fd7e18f373.jpeg)
          - Screenshot #3 Include a function to check if a user has a specific role
  - [x] \(10/28/2021) Site should have basic styles/theme applied; everything should be styled
    -  List of Evidence of Feature Completion
      - Status: Complete
      - Direct Link: https://hs723-prof.herokuapp/com/Project/home.php
      - Pull Requests
        - PR link #1 https://github.com/hwaneungsong/IT202-011/pull/20
      - Screenshots
        - Screenshot #1 ![E5179DB4-9F71-4AE7-AB21-DE42E49D988C](https://user-images.githubusercontent.com/90282169/141603993-55c8efa5-23f8-4e22-8b30-aaf170963841.jpeg)
          - Screenshot #1 Site has basic styles/themes and navigation bars
        - Screenshot #2 ![B14188DC-0EB2-4EA0-99A6-662D476BB881_4_5005_c](https://user-images.githubusercontent.com/90282169/141604314-b69e2424-140e-4123-a387-14a9cc456c24.jpeg)
          - Screenshot #2 Site has forms/input
  - [x] \(10/28/2021) Any output messages/errors should be “user friendly”
    -  List of Evidence of Feature Completion
      - Status: Complete
      - Direct Link: https://hs723-prof.herokuapp/com/Project/home.php
      - Pull Requests
        - PR link #1 https://github.com/hwaneungsong/IT202-011/pull/20
      - Screenshots
        - Screenshot #1 ![8FEB2A99-D948-4794-BA5A-B26A301FB609_4_5005_c](https://user-images.githubusercontent.com/90282169/141604500-0195534e-8094-4593-bb0d-60220d991a88.jpeg)
          - Screenshot #1 Output messages/errors should be "user friendly"
  - [x] \(11/02/2021) User will be able to see their profile
    -  List of Evidence of Feature Completion
      - Status: Complete
      - Direct Link: https://hs723-prod.herokuapp.com/Project/profile.php
      - Pull Requests
        - PR link #1 https://github.com/hwaneungsong/IT202-011/pull/21
      - Screenshots
        - Screenshot #1 ![F13887E6-EF06-4792-8A8A-3B198290C89F_4_5005_c](https://user-images.githubusercontent.com/90282169/141604652-db997b49-1df8-4087-a8b9-e640736f26bd.jpeg)
          - Screenshot #1 User will be able to see their profile
  - [x] \(11/02/2021) User will be able to edit their profile
    -  List of Evidence of Feature Completion
      - Status: Complete
      - Direct Link: https://hs723-prod.herokuapp.com/Project/profile.php
      - Pull Requests
        - PR link #1 https://github.com/hwaneungsong/IT202-011/pull/21
      - Screenshots
        - Screenshot #1 ![2110C5D4-067D-4B4D-911A-F4E9D2865014](https://user-images.githubusercontent.com/90282169/141604735-62281aaf-04a1-497f-8aa9-ab5468bda85d.jpeg)
          - Screenshot #1 User will be able to edit their profile
- Milestone 2
  - [ ] \(mm/dd/yyyy of completion) Create the Accounts table (id, account_number [unique, always 12 characters], user_id, balance (default 0), account_type, created, modified)
    -  List of Evidence of Feature Completion
      - Status: Pending (Completed, Partially working, Incomplete, Pending)
      - Direct Link: (Direct link to the file or files in heroku prod for quick testing (even if it's a protected page))
      - Pull Requests
        - PR link #1 (repeat as necessary)
      - Screenshots
        - Screenshot #1 (paste the image so it uploads to github) (repeat as necessary)
          - Screenshot #1 description explaining what you're trying to show
  - [ ] \(mm/dd/yyyy of completion) Project setup steps
    -  List of Evidence of Feature Completion
      - Status: Pending (Completed, Partially working, Incomplete, Pending)
      - Direct Link: (Direct link to the file or files in heroku prod for quick testing (even if it's a protected page))
      - Pull Requests
        - PR link #1 (repeat as necessary)
      - Screenshots
        - Screenshot #1 (paste the image so it uploads to github) (repeat as necessary)
          - Screenshot #1 description explaining what you're trying to show
  - [ ] \(mm/dd/yyyy of completion) Create the Transactions table
    -  List of Evidence of Feature Completion
      - Status: Pending (Completed, Partially working, Incomplete, Pending)
      - Direct Link: (Direct link to the file or files in heroku prod for quick testing (even if it's a protected page))
      - Pull Requests
        - PR link #1 (repeat as necessary)
      - Screenshots
        - Screenshot #1 (paste the image so it uploads to github) (repeat as necessary)
          - Screenshot #1 description explaining what you're trying to show
  - [ ] \(mm/dd/yyyy of completion) Dashboard page
    -  List of Evidence of Feature Completion
      - Status: Pending (Completed, Partially working, Incomplete, Pending)
      - Direct Link: (Direct link to the file or files in heroku prod for quick testing (even if it's a protected page))
      - Pull Requests
        - PR link #1 (repeat as necessary)
      - Screenshots
        - Screenshot #1 (paste the image so it uploads to github) (repeat as necessary)
          - Screenshot #1 description explaining what you're trying to show
  - [ ] \(mm/dd/yyyy of completion) User will be able to create a checking account
    -  List of Evidence of Feature Completion
      - Status: Pending (Completed, Partially working, Incomplete, Pending)
      - Direct Link: (Direct link to the file or files in heroku prod for quick testing (even if it's a protected page))
      - Pull Requests
        - PR link #1 (repeat as necessary)
      - Screenshots
        - Screenshot #1 (paste the image so it uploads to github) (repeat as necessary)
          - Screenshot #1 description explaining what you're trying to show
  - [ ] \(mm/dd/yyyy of completion) User will be able to list their accounts
    -  List of Evidence of Feature Completion
      - Status: Pending (Completed, Partially working, Incomplete, Pending)
      - Direct Link: (Direct link to the file or files in heroku prod for quick testing (even if it's a protected page))
      - Pull Requests
        - PR link #1 (repeat as necessary)
      - Screenshots
        - Screenshot #1 (paste the image so it uploads to github) (repeat as necessary)
          - Screenshot #1 description explaining what you're trying to show
  - [ ] \(mm/dd/yyyy of completion) User will be able to click an account for more information (a.ka. Transaction History page)
    -  List of Evidence of Feature Completion
      - Status: Pending (Completed, Partially working, Incomplete, Pending)
      - Direct Link: (Direct link to the file or files in heroku prod for quick testing (even if it's a protected page))
      - Pull Requests
        - PR link #1 (repeat as necessary)
      - Screenshots
        - Screenshot #1 (paste the image so it uploads to github) (repeat as necessary)
          - Screenshot #1 description explaining what you're trying to show
  - [ ] \(mm/dd/yyyy of completion) User will be able to deposit/withdraw from their account(s)
    -  List of Evidence of Feature Completion
      - Status: Pending (Completed, Partially working, Incomplete, Pending)
      - Direct Link: (Direct link to the file or files in heroku prod for quick testing (even if it's a protected page))
      - Pull Requests
        - PR link #1 (repeat as necessary)
      - Screenshots
        - Screenshot #1 (paste the image so it uploads to github) (repeat as necessary)
          - Screenshot #1 description explaining what you're trying to show
- Milestone 3
- Milestone 4
### Intructions
#### Don't delete this
1. Pick one project type
2. Create a proposal.md file in the root of your project directory of your GitHub repository
3. Copy the contents of the Google Doc into this readme file
4. Convert the list items to markdown checkboxes (apply any other markdown for organizational purposes)
5. Create a new Project Board on GitHub
   - Choose the Automated Kanban Board Template
   - For each major line item (or sub line item if applicable) create a GitHub issue
   - The title should be the line item text
   - The first comment should be the acceptance criteria (i.e., what you need to accomplish for it to be "complete")
   - Leave these in "to do" status until you start working on them
   - Assign each issue to your Project Board (the right-side panel)
   - Assign each issue to yourself (the right-side panel)
6. As you work
  1. As you work on features, create separate branches for the code in the style of Feature-ShortDescription (using the Milestone branch as the source)
  2. Add, commit, push the related file changes to this branch
  3. Add evidence to the PR (Feat to Milestone) conversation view comments showing the feature being implemented
     - Screenshot(s) of the site view (make sure they clearly show the feature)
     - Screenshot of the database data if applicable
     - Describe each screenshot to specify exactly what's being shown
     - A code snippet screenshot or reference via GitHub markdown may be used as an alternative for evidence that can't be captured on the screen
  4. Update the checklist of the proposal.md file for each feature this is completing (ideally should be 1 branch/pull request per feature, but some cases may have multiple)
    - Basically add an x to the checkbox markdown along with a date after
      - (i.e.,   - [x] (mm/dd/yy) ....) See Template above
    - Add the pull request link as a new indented line for each line item being completed
    - Attach any related issue items on the right-side panel
  5. Merge the Feature Branch into your Milestone branch (this should close the pull request and the attached issues)
    - Merge the Milestone branch into dev, then dev into prod as needed
    - Last two steps are mostly for getting it to prod for delivery of the assignment 
  7. If the attached issues don't close wait until the next step
  8. Merge the updated dev branch into your production branch via a pull request
  9. Close any related issues that didn't auto close
    - You can edit the dropdown on the issue or drag/drop it to the proper column on the project board