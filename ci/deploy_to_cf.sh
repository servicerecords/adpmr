#!/bin/bash
set -e

echo "============== INSTALLING CLOUD FOUNDRY CLI CLIENT =============="
# https://github.com/cloudfoundry/cli/releases
wget --max-redirect=1 --output-document=cf_cli_6.26.0.tgz "https://cli.run.pivotal.io/stable?release=linux64-binary&version=7.1.0&source=github-rel"
gunzip cf_cli_6.26.0.tgz
tar -xvf cf_cli_6.26.0.tar
ls

echo "============== LOGGING INTO CLOUD FOUNDRY =============="
echo $CF_USERNAME

# ./cf login -a=api.cloud.service.gov.uk -s=development -o=mod-request-a-historic-service-record -u=$CF_USERNAME -p=Maisie2014!


echo "============== DEPLOYING ${CF_APP_NAME} TO ${CF_SPACE} SPACE ON CLOUD FOUNDRY =============="
#ci/zero_downtime.sh
