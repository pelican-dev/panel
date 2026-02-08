#!/bin/sh -eu

# If the web server is enabled, ensure the app responds.
if [ "${SKIP_CADDY:-}" != "true" ]; then
  curl -fsS http://localhost/up >/dev/null
fi
