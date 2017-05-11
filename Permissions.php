<?php
/*

/!\ YOU ADD PERMISSIONS TO PAGES HERE /!\

WARNING:
YOU MUST FEED "SetCurrentUserType" WITH THE TYPE OF THE CURRENT USER;
IT SHOULD BE A STRING, IF YOU DON'T IT WILL USE THE FIRST TYPE OF ACCOUNT EXISTING
IF YOU HAVE NO TYPE OF ACCOUNT, SEE _conf/Constants , $ACCOUNT_TYPE

give access to an account type to an url
$this->AddPerm(url,acountlevel)
url is a string | acountlevel is a numeric index of "$ACCOUNT_TYPE"

remove access to an account type to an url
$this->RemovePerm(url,accountlevel) same
url is a string | acountlevel is a numeric index of "$ACCOUNT_TYPE"

give access to all types of account referenced in "$ACCOUNT_TYPE"
$this->AllowAll(url) give every type of account access to an url
url is a string

all pages without any permissions set will use the account level specified in there
$this->SetDefaultPerms(accountlevel)
acountlevel is a numeric index of "$ACCOUNT_TYPE"

add an exception, that means there will be no permissions checked for this page
$this->AddException(url)
url is a string

remove an exception, meaning it will have default permissions set unless you add permissions
$this->RemoveException(url)
url is a string
*/

$this->SetCurrentUserType(\Objects\User::GetType(\Session\Session::GetInstance()->GetData("user")));

$this->AddException("login");
$this->AddException("disconnect");
$this->AllowAll("championship");
$this->AllowAll("showTeams");
$this->AddPerm("clubList",0);
$this->AddPerm("handleDelays",2);
$this->AddPerm("handleDelays",1);
$this->AddPerm("reporterManagement",1);
$this->RemovePerm("reporterManagement",0);

$this->SetDefaultPerms(0);


?>