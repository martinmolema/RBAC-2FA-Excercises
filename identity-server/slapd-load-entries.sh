#!/usr/bin/bash
#
# To execute this run:
#    docker exec -it iam-example-identity-server /bin/bash -c /app/slapd-load-entries.sh
#
# Most passwords are : Test1234!

cd /app

########################################################################################################################
# First collect all the unique users from the ldap-data create user scripts and add them to the mail-role
########################################################################################################################

# create a new file
touch role_assignment_mail.lst

# Now split the teachers and students in two lists per course
grep 'dn:' Ldap-data-02a-Create-Users-Opleiding-AD.ldif | awk -F \: '/ou=Teachers/{print "uniqueMember:" $2}' > role_assignment_teachers-ADCSS.lst
grep 'dn:' Ldap-data-02a-Create-Users-Opleiding-AD.ldif | awk -F \: '/ou=Students/{print "uniqueMember:" $2}' > role_assignment_students-ADCSS.lst

grep 'dn:' Ldap-data-02b-Create-Users-Opleiding-HBO-ICT.ldif | awk -F \: '/ou=Teachers/{print "uniqueMember:" $2}' > role_assignment_teachers-HBOICT.lst
grep 'dn:' Ldap-data-02b-Create-Users-Opleiding-HBO-ICT.ldif | awk -F \: '/ou=Students/{print "uniqueMember:" $2}' > role_assignment_students-HBOICT.lst

# Now duplicate two teachers from each course to the other course: these teachers will work at two courses
head -n 2 role_assignment_teachers-ADCSS.lst >> role_assignment_teachers-HBOICT.lst

# Collect all teachers in one list
cat role_assignment_teachers-ADCSS.lst role_assignment_teachers-HBOICT.lst | sort | uniq > role_assignment_all_teachers.lst

# Collect all students in one list
cat role_assignment_students-ADCSS.lst role_assignment_students-HBOICT.lst > role_assignment_all_students.lst

# Now assign these list also to a different role for grades
cp role_assignment_all_teachers.lst role_assignment_grades-teachers.lst
cp role_assignment_all_students.lst role_assignment_grades-students.lst

# Now process the marketing users; these should land in two groups: the basic Marketing group to access the application and a group for the basic permissions
grep 'dn:' Ldap-data-03-Create-Users-marketing.ldif | awk -F \: '/dn:/{print "uniqueMember:" $2}' > role_assignment_marketing.lst

# Leave out the last three members; they will be promoted to management in the Marketing Managers group.
grep 'dn:' Ldap-data-03-Create-Users-marketing.ldif | awk -F \: '/dn:/{print "uniqueMember:" $2}' | head -n -3 > role_assignment_marketing_employees.lst

# Make the last 3 users member of the marketing role; just take the last three items from the list
grep 'dn:' Ldap-data-03-Create-Users-marketing.ldif | tail -n 3 | awk -F \: '/dn:/{print "uniqueMember:" $2}' > role_assignment_marketing-managers.lst

# Now process the ICT Support users
grep 'dn:' Ldap-data-04-Create-Users-ict_support.ldif | awk -F \: '/dn:/{print "uniqueMember:" $2}' > role_assignment_ict-support.lst

# Now process the HRM users
grep 'dn:' Ldap-data-05-Create-Users-HRM.ldif | awk -F \: '/dn:/{print "uniqueMember:" $2}' > role_assignment_hrm.lst


# Copy for access to SharePoint sub-parts for teachers and students.
cp role_assignment_all_teachers.lst role_assignment_sharepoint-teachers.lst
cp role_assignment_all_students.lst role_assignment_sharepoint-students.lst

# Collect all personell in one list
cat role_assignment_grades-teachers.lst role_assignment_marketing.lst role_assignment_ict-support.lst role_assignment_hrm.lst > role_assignment_all_personell.lst

for LDIF_FILE in ./Ldap-data-[0-9]*.ldif ; do
 echo "Adding  file $LDIF_FILE" >&2
 ldapadd -x -D cn=admin,dc=NHLStenden,dc=com -w test12345! -f $LDIF_FILE
done;

# Finally create LDIF files from the base role and add the list of users.
echo "Creating roles and members" >&2

# Loop through all the .base files, add the user list and execute in LDAP
for ROLE_FILE in *.base ;  do
  NEW_FILENAME="${ROLE_FILE%.base}.ldif"
  USERS_LIST_FILENAME="${ROLE_FILE%.base}.lst"

  if [ -f "$USERS_LIST_FILENAME" ] ; then

    echo "----------------------------------------------------------------------------------------------"
    echo "Found file: $ROLE_FILE"
    echo "Creating LDIF $NEW_FILENAME"
    echo "Adding users from $USERS_LIST_FILENAME"
    NrOfEntries=`wc -l $USERS_LIST_FILENAME`
    echo "- Found $NrOfEntries  entries"

    cp $ROLE_FILE $NEW_FILENAME
    cat $USERS_LIST_FILENAME >> $NEW_FILENAME
    ldapadd -x -D cn=admin,dc=NHLStenden,dc=com -w test12345! -f $NEW_FILENAME
  fi
done

rm /app/*.base /app/*.ldif /app/*.lst

########################################################################################
########################################################################################
############# NOW UPLOAD THE IMAGES ####################################################
########################################################################################
########################################################################################

# Create Python virtual environment
python3 -m venv /app/ldap-updates-venv
cd /app/ldap-updates-venv

# Activate virtual environment
source bin/activate
pip install ldap3
cd /app

python3 /app/upload_avatars.py
python3 /app/add-more-info.py

cd /app
source venv/bin/activate
python3 /app/import_once_from_ldap_to_db.py
deactivate

########################################################################################
########################################################################################
############# CLEANUP               ####################################################
########################################################################################
########################################################################################

# when all went well....
rm avatars/*.jpeg
rmdir avatars
rm -rf /app/ldap-updates-venv
rm /app/*.py

apt purge -y python3 python3-pip && apt -y autoremove
