#!/bin/sh
#
# Copyright (C) 2003-2013 eXo Platform SAS.
#
# This is free software; you can redistribute it and/or modify it
# under the terms of the GNU Lesser General Public License as
# published by the Free Software Foundation; either version 3 of
# the License, or (at your option) any later version.
#
# This software is distributed in the hope that it will be useful,
# but WITHOUT ANY WARRANTY; without even the implied warranty of
# MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the GNU
# Lesser General Public License for more details.
#
# You should have received a copy of the GNU Lesser General Public
# License along with this software; if not, write to the Free
# Software Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA
# 02110-1301 USA, or see the FSF site: http://www.fsf.org.
#

# -----------------------------------------------------------------------------
# Load environment specific settings
# -----------------------------------------------------------------------------
[ -e ${CATALINA_HOME}/bin/setenv-local.sh ] && . ${CATALINA_HOME}/bin/setenv-local.sh

# -----------------------------------------------------------------------------
# Update CATALINA_OPTS entries
# -----------------------------------------------------------------------------
# JVM
CATALINA_OPTS="${CATALINA_OPTS} -XX:+HeapDumpOnOutOfMemoryError"
CATALINA_OPTS="${CATALINA_OPTS} -XX:HeapDumpPath=${CATALINA_HOME}/logs/"
# JMX
CATALINA_OPTS="${CATALINA_OPTS} -Dcom.sun.management.jmxremote=true"
CATALINA_OPTS="${CATALINA_OPTS} -Dcom.sun.management.jmxremote.ssl=false"
CATALINA_OPTS="${CATALINA_OPTS} -Dcom.sun.management.jmxremote.password.file=${CATALINA_HOME}/conf/jmxremote.password"
CATALINA_OPTS="${CATALINA_OPTS} -Dcom.sun.management.jmxremote.access.file=${CATALINA_HOME}/conf/jmxremote.access"
CATALINA_OPTS="${CATALINA_OPTS} -Djava.rmi.server.hostname=${EXO_JVM_JMX_REMOTE_HOSTNAME}"
# CRaSH
if ${DEPLOYMENT_CRASH_ENABLED}; then
    CATALINA_OPTS="${CATALINA_OPTS} -Dcrash.telnet.port=${EXO_CRASH_TELNET_PORT}"
    CATALINA_OPTS="${CATALINA_OPTS} -Dcrash.ssh.port=${EXO_CRASH_SSH_PORT}"
fi
# Elasticsearch Embedded
if ${DEPLOYMENT_ES_ENABLED}; then
    CATALINA_OPTS="${CATALINA_OPTS} -Dexo.es.embedded.enabled=${DEPLOYMENT_ES_EMBEDDED}"
    CATALINA_OPTS="${CATALINA_OPTS} -Des.http.port=${EXO_ES_HTTP_PORT}"
    CATALINA_OPTS="${CATALINA_OPTS} -Dexo.es.index.server.url=http://127.0.0.1:${EXO_ES_HTTP_PORT}"
    CATALINA_OPTS="${CATALINA_OPTS} -Dexo.es.search.server.url=http://127.0.0.1:${EXO_ES_HTTP_PORT}"
    CATALINA_OPTS="${CATALINA_OPTS} -Des.path.data=${CATALINA_HOME}/${EXO_ES_PATH_DATA}"
    CATALINA_OPTS="${CATALINA_OPTS} -Des.node.name=${INSTANCE_KEY}"
fi
# eXo Addon Chat
if ${DEPLOYMENT_CHAT_ENABLED}; then
    if ! ${DEPLOYMENT_CHAT_EMBEDDED}; then
        CATALINA_OPTS="${CATALINA_OPTS} -Dchat.standaloneChatServer=true"
        CATALINA_OPTS="${CATALINA_OPTS} -Dchat.chatServerBase=http://localhost:${DEPLOYMENT_CHAT_SERVER_PORT}"
    else 
        CATALINA_OPTS="${CATALINA_OPTS} -Dchat.dbServerHost=${EXO_CHAT_MONGODB_HOSTNAME}"
        CATALINA_OPTS="${CATALINA_OPTS} -Dchat.dbServerPort=${EXO_CHAT_MONGODB_PORT}"
    fi
    CATALINA_OPTS="${CATALINA_OPTS} -Dchat.weemoKey=${EXO_CHAT_WEEMO_KEY}"
    CATALINA_OPTS="${CATALINA_OPTS} -Dchat.dbName=${EXO_CHAT_MONGODB_NAME}"
    CATALINA_OPTS="${CATALINA_OPTS} -Dchat.chatPassPhrase=${EXO_CHAT_MONGODB_NAME}"
fi
# Skip register form
if ${DEPLOYMENT_SKIP_REGISTER}; then
    CATALINA_OPTS="${CATALINA_OPTS} -Dexo.registration.skip=true"
fi
# Skip account creation form
CATALINA_OPTS="${CATALINA_OPTS} -Daccountsetup.skip=${EXO_SKIP_ACCOUNT_SETUP}"
# JMXTrans Addon
if ${DEPLOYMENT_JMXTRANS_ADDON_ENABLED}; then
  CATALINA_OPTS="${CATALINA_OPTS} -Dgraphite.host=${EXO_JMXTRANS_ADDON_HOST}"
  CATALINA_OPTS="${CATALINA_OPTS} -Dgraphite.port=${EXO_JMXTRANS_ADDON_PORT}"
  CATALINA_OPTS="${CATALINA_OPTS} -Dgraphite.namePrefix=acceptance.${EXO_JMXTRANS_CATEGORY}."
  CATALINA_OPTS="${CATALINA_OPTS} -Dgraphite.enabled=true"
  CATALINA_OPTS="${CATALINA_OPTS} -Dgraphite.pickle.enabled=false"

  CATALINA_OPTS="${CATALINA_OPTS} -Dgraphite.queryIntervalInSeconds=15"
  CATALINA_OPTS="${CATALINA_OPTS} -Dgraphite.queryThreads=15"
  CATALINA_OPTS="${CATALINA_OPTS} -Dgraphite.exportIntervalInSeconds=30"
  CATALINA_OPTS="${CATALINA_OPTS} -Dgraphite.exportBatchSize=50"
  CATALINA_OPTS="${CATALINA_OPTS} -Dgraphite.numExportThreads=5"
fi