#! /bin/bash

BASE_DIR=/var/www/html/webERP;
OUTPUT_DIR=/home/user/Documents/webERP;
MYSQL_USER=root;
MYSQL_PWD=a;

cd $BASE_DIR;

# xgettext: Extracts translatable strings from given input file paths

xgettext --no-wrap --from-code=utf-8 -L php -o locale/en_GB.utf8/LC_MESSAGES/messages.pot *.php api/*.php includes/*.inc includes/*.php install/*.php reportwriter/*.inc reportwriter/*.php reportwriter/admin/*.inc reportwriter/admin/*.php reportwriter/admin/forms/*.html reportwriter/forms/*.html reportwriter/languages/en_US/*.php ../webSHOP/*.php ../webSHOP/includes/*.php

# msgmerge: Merges two Uniforum style .po files together

msgmerge -U -N --backup=off --no-wrap locale/ar_EG.utf8/LC_MESSAGES/messages.po locale/en_GB.utf8/LC_MESSAGES/messages.pot
msgmerge -U -N --backup=off --no-wrap locale/ar_SY.utf8/LC_MESSAGES/messages.po locale/en_GB.utf8/LC_MESSAGES/messages.pot
msgmerge -U -N --backup=off --no-wrap locale/cs_CZ.utf8/LC_MESSAGES/messages.po locale/en_GB.utf8/LC_MESSAGES/messages.pot
msgmerge -U -N --backup=off --no-wrap locale/de_DE.utf8/LC_MESSAGES/messages.po locale/en_GB.utf8/LC_MESSAGES/messages.pot
msgmerge -U -N --backup=off --no-wrap locale/el_GR.utf8/LC_MESSAGES/messages.po locale/en_GB.utf8/LC_MESSAGES/messages.pot
msgmerge -U -N --backup=off --no-wrap locale/en_US.utf8/LC_MESSAGES/messages.po locale/en_GB.utf8/LC_MESSAGES/messages.pot
msgmerge -U -N --backup=off --no-wrap locale/es_ES.utf8/LC_MESSAGES/messages.po locale/en_GB.utf8/LC_MESSAGES/messages.pot
msgmerge -U -N --backup=off --no-wrap locale/et_EE.utf8/LC_MESSAGES/messages.po locale/en_GB.utf8/LC_MESSAGES/messages.pot
msgmerge -U -N --backup=off --no-wrap locale/fa_IR.utf8/LC_MESSAGES/messages.po locale/en_GB.utf8/LC_MESSAGES/messages.pot
msgmerge -U -N --backup=off --no-wrap locale/fr_CA.utf8/LC_MESSAGES/messages.po locale/en_GB.utf8/LC_MESSAGES/messages.pot
msgmerge -U -N --backup=off --no-wrap locale/fr_FR.utf8/LC_MESSAGES/messages.po locale/en_GB.utf8/LC_MESSAGES/messages.pot
msgmerge -U -N --backup=off --no-wrap locale/he_IL.utf8/LC_MESSAGES/messages.po locale/en_GB.utf8/LC_MESSAGES/messages.pot
msgmerge -U -N --backup=off --no-wrap locale/hi_IN.utf8/LC_MESSAGES/messages.po locale/en_GB.utf8/LC_MESSAGES/messages.pot
msgmerge -U -N --backup=off --no-wrap locale/hr_HR.utf8/LC_MESSAGES/messages.po locale/en_GB.utf8/LC_MESSAGES/messages.pot
msgmerge -U -N --backup=off --no-wrap locale/hu_HU.utf8/LC_MESSAGES/messages.po locale/en_GB.utf8/LC_MESSAGES/messages.pot
msgmerge -U -N --backup=off --no-wrap locale/id_ID.utf8/LC_MESSAGES/messages.po locale/en_GB.utf8/LC_MESSAGES/messages.pot
msgmerge -U -N --backup=off --no-wrap locale/it_IT.utf8/LC_MESSAGES/messages.po locale/en_GB.utf8/LC_MESSAGES/messages.pot
msgmerge -U -N --backup=off --no-wrap locale/ja_JP.utf8/LC_MESSAGES/messages.po locale/en_GB.utf8/LC_MESSAGES/messages.pot
msgmerge -U -N --backup=off --no-wrap locale/ko_KR.utf8/LC_MESSAGES/messages.po locale/en_GB.utf8/LC_MESSAGES/messages.pot
msgmerge -U -N --backup=off --no-wrap locale/lv_LV.utf8/LC_MESSAGES/messages.po locale/en_GB.utf8/LC_MESSAGES/messages.pot
msgmerge -U -N --backup=off --no-wrap locale/mr_IN.utf8/LC_MESSAGES/messages.po locale/en_GB.utf8/LC_MESSAGES/messages.pot
msgmerge -U -N --backup=off --no-wrap locale/nl_NL.utf8/LC_MESSAGES/messages.po locale/en_GB.utf8/LC_MESSAGES/messages.pot
msgmerge -U -N --backup=off --no-wrap locale/pl_PL.utf8/LC_MESSAGES/messages.po locale/en_GB.utf8/LC_MESSAGES/messages.pot
msgmerge -U -N --backup=off --no-wrap locale/pt_BR.utf8/LC_MESSAGES/messages.po locale/en_GB.utf8/LC_MESSAGES/messages.pot
msgmerge -U -N --backup=off --no-wrap locale/pt_PT.utf8/LC_MESSAGES/messages.po locale/en_GB.utf8/LC_MESSAGES/messages.pot
msgmerge -U -N --backup=off --no-wrap locale/ro_RO.utf8/LC_MESSAGES/messages.po locale/en_GB.utf8/LC_MESSAGES/messages.pot
msgmerge -U -N --backup=off --no-wrap locale/ru_RU.utf8/LC_MESSAGES/messages.po locale/en_GB.utf8/LC_MESSAGES/messages.pot
msgmerge -U -N --backup=off --no-wrap locale/sq_AL.utf8/LC_MESSAGES/messages.po locale/en_GB.utf8/LC_MESSAGES/messages.pot
msgmerge -U -N --backup=off --no-wrap locale/sv_SE.utf8/LC_MESSAGES/messages.po locale/en_GB.utf8/LC_MESSAGES/messages.pot
msgmerge -U -N --backup=off --no-wrap locale/sw_KE.utf8/LC_MESSAGES/messages.po locale/en_GB.utf8/LC_MESSAGES/messages.pot
msgmerge -U -N --backup=off --no-wrap locale/tr_TR.utf8/LC_MESSAGES/messages.po locale/en_GB.utf8/LC_MESSAGES/messages.pot
msgmerge -U -N --backup=off --no-wrap locale/vi_VN.utf8/LC_MESSAGES/messages.po locale/en_GB.utf8/LC_MESSAGES/messages.pot
msgmerge -U -N --backup=off --no-wrap locale/zh_CN.utf8/LC_MESSAGES/messages.po locale/en_GB.utf8/LC_MESSAGES/messages.pot
msgmerge -U -N --backup=off --no-wrap locale/zh_HK.utf8/LC_MESSAGES/messages.po locale/en_GB.utf8/LC_MESSAGES/messages.pot
msgmerge -U -N --backup=off --no-wrap locale/zh_TW.utf8/LC_MESSAGES/messages.po locale/en_GB.utf8/LC_MESSAGES/messages.pot

# msgfmt: Generates a binary message catalog from a textual translation description

msgfmt -o locale/ar_EG.utf8/LC_MESSAGES/messages.mo locale/ar_EG.utf8/LC_MESSAGES/messages.po
msgfmt -o locale/ar_SY.utf8/LC_MESSAGES/messages.mo locale/ar_SY.utf8/LC_MESSAGES/messages.po
msgfmt -o locale/cs_CZ.utf8/LC_MESSAGES/messages.mo locale/cs_CZ.utf8/LC_MESSAGES/messages.po
msgfmt -o locale/de_DE.utf8/LC_MESSAGES/messages.mo locale/de_DE.utf8/LC_MESSAGES/messages.po
msgfmt -o locale/el_GR.utf8/LC_MESSAGES/messages.mo locale/el_GR.utf8/LC_MESSAGES/messages.po
msgfmt -o locale/en_US.utf8/LC_MESSAGES/messages.mo locale/en_US.utf8/LC_MESSAGES/messages.po
msgfmt -o locale/es_ES.utf8/LC_MESSAGES/messages.mo locale/es_ES.utf8/LC_MESSAGES/messages.po
msgfmt -o locale/et_EE.utf8/LC_MESSAGES/messages.mo locale/et_EE.utf8/LC_MESSAGES/messages.po
msgfmt -o locale/fa_IR.utf8/LC_MESSAGES/messages.mo locale/fa_IR.utf8/LC_MESSAGES/messages.po
msgfmt -o locale/fr_CA.utf8/LC_MESSAGES/messages.mo locale/fr_CA.utf8/LC_MESSAGES/messages.po
msgfmt -o locale/fr_FR.utf8/LC_MESSAGES/messages.mo locale/fr_FR.utf8/LC_MESSAGES/messages.po
msgfmt -o locale/he_IL.utf8/LC_MESSAGES/messages.mo locale/he_IL.utf8/LC_MESSAGES/messages.po
msgfmt -o locale/hi_IN.utf8/LC_MESSAGES/messages.mo locale/hi_IN.utf8/LC_MESSAGES/messages.po
msgfmt -o locale/hr_HR.utf8/LC_MESSAGES/messages.mo locale/hr_HR.utf8/LC_MESSAGES/messages.po
msgfmt -o locale/hu_HU.utf8/LC_MESSAGES/messages.mo locale/hu_HU.utf8/LC_MESSAGES/messages.po
msgfmt -o locale/id_ID.utf8/LC_MESSAGES/messages.mo locale/id_ID.utf8/LC_MESSAGES/messages.po
msgfmt -o locale/it_IT.utf8/LC_MESSAGES/messages.mo locale/it_IT.utf8/LC_MESSAGES/messages.po
msgfmt -o locale/ja_JP.utf8/LC_MESSAGES/messages.mo locale/ja_JP.utf8/LC_MESSAGES/messages.po
msgfmt -o locale/ko_KR.utf8/LC_MESSAGES/messages.mo locale/ko_KR.utf8/LC_MESSAGES/messages.po
msgfmt -o locale/lv_LV.utf8/LC_MESSAGES/messages.mo locale/lv_LV.utf8/LC_MESSAGES/messages.po
msgfmt -o locale/mr_IN.utf8/LC_MESSAGES/messages.mo locale/mr_IN.utf8/LC_MESSAGES/messages.po
msgfmt -o locale/nl_NL.utf8/LC_MESSAGES/messages.mo locale/nl_NL.utf8/LC_MESSAGES/messages.po
msgfmt -o locale/pl_PL.utf8/LC_MESSAGES/messages.mo locale/pl_PL.utf8/LC_MESSAGES/messages.po
msgfmt -o locale/pt_BR.utf8/LC_MESSAGES/messages.mo locale/pt_BR.utf8/LC_MESSAGES/messages.po
msgfmt -o locale/pt_PT.utf8/LC_MESSAGES/messages.mo locale/pt_PT.utf8/LC_MESSAGES/messages.po
msgfmt -o locale/ro_RO.utf8/LC_MESSAGES/messages.mo locale/ro_RO.utf8/LC_MESSAGES/messages.po
msgfmt -o locale/ru_RU.utf8/LC_MESSAGES/messages.mo locale/ru_RU.utf8/LC_MESSAGES/messages.po
msgfmt -o locale/sq_AL.utf8/LC_MESSAGES/messages.mo locale/sq_AL.utf8/LC_MESSAGES/messages.po
msgfmt -o locale/sv_SE.utf8/LC_MESSAGES/messages.mo locale/sv_SE.utf8/LC_MESSAGES/messages.po
msgfmt -o locale/sw_KE.utf8/LC_MESSAGES/messages.mo locale/sw_KE.utf8/LC_MESSAGES/messages.po
msgfmt -o locale/tr_TR.utf8/LC_MESSAGES/messages.mo locale/tr_TR.utf8/LC_MESSAGES/messages.po
msgfmt -o locale/vi_VN.utf8/LC_MESSAGES/messages.mo locale/vi_VN.utf8/LC_MESSAGES/messages.po
msgfmt -o locale/zh_CN.utf8/LC_MESSAGES/messages.mo locale/zh_CN.utf8/LC_MESSAGES/messages.po
msgfmt -o locale/zh_HK.utf8/LC_MESSAGES/messages.mo locale/zh_HK.utf8/LC_MESSAGES/messages.po
msgfmt -o locale/zh_TW.utf8/LC_MESSAGES/messages.mo locale/zh_TW.utf8/LC_MESSAGES/messages.po

mysql -u$MYSQL_USER  -p$MYSQL_PWD < $BASE_DIR/build/TruncateAuditTrail.sql

echo "SET FOREIGN_KEY_CHECKS = 0;" > $BASE_DIR/sql/mysql/country_sql/default.sql

mysqldump -u$MYSQL_USER  -p$MYSQL_PWD  --skip-opt --create-options --skip-set-charset --ignore-table=weberpdemo.mrpsupplies  --ignore-table=weberpdemo.mrpplanedorders --ignore-table=weberpdemo.mrpparameters --ignore-table=weberpdemo.levels --ignore-table=weberpdemo.mrprequirements --ignore-table=weberpdemo.buckets --no-data weberpdemo | sed 's/ AUTO_INCREMENT=[0-9]*//g' >> $BASE_DIR/sql/mysql/country_sql/default.sql

mysqldump -u$MYSQL_USER  -p$MYSQL_PWD --skip-opt --skip-set-charset --quick --no-create-info weberpdemo  \
       accountgroups \
       bankaccounts \
       chartmaster \
       companies \
       cogsglpostings \
       currencies \
       holdreasons \
       locations \
       paymentterms \
       reportlinks \
       salesglpostings \
       systypes \
       taxauthorities \
       taxgroups \
       taxauthrates \
       taxcategories \
       taxprovinces \
       www_users \
       edi_orders_segs \
       edi_orders_seg_groups \
       config \
       unitsofmeasure \
       paymentmethods \
       scripts \
       securitygroups \
       securitytokens \
       securityroles \
       accountsection \
       > $BASE_DIR/sql/mysql/country_sql/weberp-base.sql

mysqldump -u$MYSQL_USER  -p$MYSQL_PWD --skip-opt --skip-set-charset --quick --ignore-table=weberpdemo.mrpsupplies  --ignore-table=weberpdemo.mrpplanedorders --ignore-table=weberpdemo.mrpparameters --ignore-table=weberpdemo.levels --ignore-table=weberpdemo.mrprequirements --no-create-info weberpdemo  > $BASE_DIR/sql/mysql/country_sql/weberp-demo_data.sql

rm  $BASE_DIR/sql/mysql/country_sql/demo.sql
echo "CREATE DATABASE IF NOT EXISTS weberpdemo;" > $BASE_DIR/sql/mysql/country_sql/demo.sql
echo "USE weberpdemo;" >> $BASE_DIR/sql/mysql/country_sql/demo.sql

cat $BASE_DIR/sql/mysql/country_sql/default.sql >> $BASE_DIR/sql/mysql/country_sql/demo.sql

cat $BASE_DIR/sql/mysql/country_sql/weberp-base.sql >> $BASE_DIR/sql/mysql/country_sql/default.sql
cat $BASE_DIR/sql/mysql/country_sql/weberp-demo_data.sql >> $BASE_DIR/sql/mysql/country_sql/demo.sql
rm  $BASE_DIR/sql/mysql/country_sql/weberp-demo_data.sql
rm  $BASE_DIR/sql/mysql/country_sql/weberp-base.sql

echo "SET FOREIGN_KEY_CHECKS = 1;" >> $BASE_DIR/sql/mysql/country_sql/default.sql
echo "UPDATE systypes SET typeno=0;" >> $BASE_DIR/sql/mysql/country_sql/default.sql
echo "INSERT INTO shippers VALUES (1,'Default Shipper',0);" >> $BASE_DIR/sql/mysql/country_sql/default.sql
echo "UPDATE config SET confvalue='1' WHERE confname='Default_Shipper';" >> $BASE_DIR/sql/mysql/country_sql/default.sql
echo "SET FOREIGN_KEY_CHECKS = 1;" >> $BASE_DIR/sql/mysql/country_sql/demo.sql

rm $OUTPUT_DIR/webERP.zip

cd ..

zip -r $OUTPUT_DIR/webERP webERP webSHOP -x \*.git* \*/config.php \*build*
