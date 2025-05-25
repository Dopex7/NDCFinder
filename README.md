# NDCFinder

NDCFinder is a Laravel-based web application that allows users to search, save, and manage NDC (National Drug Code) products. It integrates with the OpenFDA API to fetch drug information that is not already stored locally in the database.

## Installation (Udhëzime instalimi)

1. Clone the repository:  
   ```bash
   git clone https://github.com/Dopex7/NDCFinder.git
   cd NDCFinder

   Logic Description (Përshkrim i shkurtër i logjikës së implementuar)
-The application lets users search for one or more NDC codes at once.

-It first checks if the codes exist in the local database.

-Missing codes are fetched from the OpenFDA API in bulk to reduce requests.

-Retrieved API results are saved locally for future queries.

-Users can view saved products, delete them, and export the list as CSV.

-The app requires users to be authenticated for all features.
