#!/bin/bash
set -e

# ENSURE REQUIRED VARS ARE SET:
if [ -z ${CF_MANIFEST+x} ]; then
  echo "Please set CF_MANIFEST with the filename of the manifest to use for the deploy."
  exit 1
fi

if [ -z ${CF_APP_NAME+x} ]; then
  echo "Please set CF_APP_NAME with the name of the app to be deployed."
  exit 1
fi

CF_DOCKER_PASSWORD=${AWS_SECRET_ACCESS_KEY} ./cf push -f ${CF_MANIFEST} --strategy rolling --docker-image ${AWS_CONTAINER_IMAGE} --docker-username ${AWS_ACCESS_KEY_ID}
