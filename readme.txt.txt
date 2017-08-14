1. Drop the app folder in the root directory of your Magento instance
2. Delete all files/folders in <Magento root>/var/cache/*
3. Delete all files/folders in <Magento root>/var/session/*
4. Go to System Configuration->Sendbox Configuration and set the config options and save
5. Go to System Configuration->Shipping Methods->Sendbox Custom Shipping and set the config options
   Select Nigeria under "Ship to Specific Countries" and save
6. If your Magento instance doesnnot have states in Nigeria as a drop-down,kindly run the query in the file "nigerian-states_sql.sql",if you have table pre-fix,kindly update as appriopriate in the sql file.

N/B: Ensure all products have weight to get accurate shipping quote.