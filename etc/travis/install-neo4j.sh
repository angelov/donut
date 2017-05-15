#!/bin/bash

# Taken from https://github.com/graphaware/reco4php

export JAVA_HOME=/usr/lib/jvm/java-8-oracle
export JRE_HOME=/usr/lib/jvm/java-8-oracle

wget http://dist.neo4j.org/neo4j-community-3.2.0-unix.tar.gz > null
mkdir neo
tar xzf neo4j-community-3.2.0-unix.tar.gz -C neo --strip-components=1 > null
sed -i.bak '/\(dbms\.security\.auth_enabled=\).*/s/^#//g' ./neo/conf/neo4j.conf
neo/bin/neo4j start > null &
