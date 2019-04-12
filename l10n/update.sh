#!/bin/bash

git checkout l10n_master
git reset --hard HEAD~200
git pull http l10n_master
git rebase master
git reset --soft master
git commit -m "new translations from crowdin"
git checkout master
echo "ready to merge l10n_master"
