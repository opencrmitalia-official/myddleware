#!/bin/bash
set -e

## Prepara localmente una seconda copia del progetto Myddleware e procede ad utilizzarla come base da aggiornare
[[ -d "CLONE" ]] || git clone https://github.com/opencrmitalia-official/myddleware.git CLONE

## Mi sposto sul progetto nuovo appena scaricato
cd CLONE

## Cancelliamo la nostra branch hotfix e la sostituiamo con quelle dell'UPSTREAM
#git push origin --delete hotfix || true
#git checkout master
#git checkout -b hotfix
#git pull https://github.com/Myddleware/myddleware.git hotfix -X theirs --no-edit
#git push --set-upstream origin hotfix

## Aggiorna la branch master del nostro progetto con le novità presenti nel master dell'UPSTRAM
echo "==> Update: main"
git checkout main
git pull https://github.com/Myddleware/myddleware.git main -X theirs --no-edit --no-rebase
git push

## Aggiorna la branch master del nostro progetto con le novità presenti nel master dell'UPSTRAM
echo "==> Update: master"
git checkout master
git pull https://github.com/Myddleware/myddleware.git master -X theirs --no-edit --no-rebase
git push

## Aggiorna la branch contribute con le novità presenti nel master dell'UPSTRAM
echo "==> Update: contrib"
git checkout contrib
git pull https://github.com/Myddleware/myddleware.git master -X theirs --no-edit --no-rebase
git push

## Aggiorna la branch contribute con le novità presenti nel master dell'UPSTRAM
echo "==> Update: squash"
git checkout squash
git pull https://github.com/Myddleware/myddleware.git master -X theirs --no-edit --no-rebase
git push

cd ..
rm -fr CLONE
