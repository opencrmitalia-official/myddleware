# Sync Upstream

> Before run this procedure read the file [REPOSITORY.md](REPOSITORY.md)

This operation is for get updates from the UPSTREAM repository, follow this steps:

1. Go to `devops` branch with the following command `git checkout devops` than run a `git pull`;
2. Run the this script `./dev/script/update-from-upstream.sh` or use BASH interpreter with `bash dev/script/update-from-upstream.sh`
3. Verify if the last commit in this page <https://github.com/Myddleware/myddleware/commits/master> is included in this page <https://github.com/opencrmitalia-official/myddleware/commits/master>
4. Verify if the last commit in this page <https://github.com/Myddleware/myddleware/commits/master> is included in this page <https://github.com/opencrmitalia-official/myddleware/commits/contrib>
5. Now go to this page <https://github.com/opencrmitalia-official/myddleware/compare/stable...opencrmitalia-official:master?expand=1> and click "Create Pull-request" button
6. If there is no error click on "Merge pull request" button and then "Confirm merge" button.
7. In your local project launch `git checkout stable` and `git pull` and follow installation instruction provided [here](DEBUG.md)
