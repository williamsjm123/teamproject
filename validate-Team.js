
function validateTeam() {
	
	var firstName = document.forms[0].firstName; //Access team member's firstname
	var lastName = document.forms[0].lastName; //Access team member's last name
	var gender = document.forms[0].gender; //Access team member's gender
	var birthday =  document.forms[0].dateOfBirth;//Access team member's date of birth
	var userName = document.forms[0].userName; //Access team member's username
	var userPassword = document.forms[0].userPassword; //Access team member's password
	
	var now = new Date(); //Today's date
	var thisDay = new Date(now.getFullYear(), now.getMonth(), now.getDate());//Today's date calculated from midnight
	var dob = new Date(birthday.value); //Start date
	var years = thisDay.getFullYear() - dob.getFullYear(); //Calculate the team members age in years
	
	var pattern = /^[A-Za-z]+$/; //pattern for first name, lastname, gender 
	var reg = /^[A-Za-z\s]+$/; //pattern for team member status
	var regex = /^[\w]+$/; //pattern for username
	var pswd = /^([\w@.\s\$\*£%\+=]{1,255})$/; //pattern match for password
	var errorMessage = "There area few errors with the form inputs: \n"; //Error message to be displayed
	var errorNum = 0;	//Number of errors found, also used to index error message
	
	//validate input for firstname
	if (firstName.value == ""){
	errorNum++;
	errorMessage += errorNum + ". Please fill in your first name! \n";
	firstName.focus();
	}
	
	//checking for pattern match in first name
	if (!firstName.value.match(pattern)) {
		errorNum++;
	errorMessage += errorNum + ". Please enter a valid first name! \n";
		firstName.focus();
	}
	
	//validate input for lastname
	if (lastName.value == "") {
		errorNum++;
	errorMessage += errorNum + ". Please fill in your last name! \n";
		lastName.focus();
	}
	
	//checking for pattern match in last name
	if (!lastName.value.match(pattern)) {
		errorNum++;
	errorMessage += errorNum + ". Please enter a valid last name! \n";
		lastName.focus();
	}
	
	
	//validate input for gender
	if (gender.value == "") {
		errorNum++;
	errorMessage += errorNum + ". Please provide an input for gender! \n";
		gender.focus();
	}
	
	//checking for pattern match for gender
	if (!gender.value.match(pattern)) {
		errorNum++;
	errorMessage += errorNum + ". Please enter a valid input  for gender! \n";
		gender.focus();
	}
	
	
	//validate user's date of birth
	// Compare dates by comparing the millisecond representations. 
	if (birthday.value == "") {
		errorNum++;
	errorMessage += errorNum + ". Please enter a valid date of birth! \n";
		birthday.focus();
	} else if (dob.getTime() >= thisDay.getTime()) {
		errorNum++;
	errorMessage += errorNum + ". Date of birth cannot be equal to or later than today! \n";
		birthday.focus();
	} else if (years < 18) {
		errorNum++;
	errorMessage += errorNum + ". A team member must be 18 or over! \n";
		birthday.focus(); 
	} else if (years == 18 && dob.getMonth() < thisDay.getMonth()) {
		errorNum++;
	errorMessage += errorNum + ". A team member must be 18 or over! \n";
		birthday.focus(); 
	}
	
	
	//accessing team members status
	//validate input for status
	if (role.value == ""){
	errorNum++;
	errorMessage += errorNum + ". Please enter a status for team member! \n";
	role.focus();
	}
	
	//checking for pattern match for team member status
	if (!role.value.match(reg)) {
		errorNum++;
	errorMessage += errorNum + ". Please enter a valid status for team member! \n";
		role.focus();
	}
	
	
	//validate input for username
	if (userName.value == ""){
	errorNum++;
	errorMessage += errorNum + ". Please fill in your user name! \n";
	userName.focus();
	}
	
	//checking for pattern match in user name
	if (!userName.value.match(regex)) {
		errorNum++;
	errorMessage += errorNum + ". Please enter a valid user name! \n";
		userName.focus();
	}
	
	
	//validate input for password
	if (userPassword.value == ""){
	errorNum++;
	errorMessage += errorNum + ". Please enter your password! \n";
	userPassword.focus();
	}
	
	//checking for pattern match in password
	if (!userPassword.value.match(pswd)) {
		errorNum++;
	errorMessage += errorNum + ". Please enter a valid password! \n";
		userPassword.focus();
	}
	
	//if the number of errors is greater than 0, an alert containing error message will be displayed
	if (errorNum > 0) {
		alert(errorMessage);
		return false;
	}
	
	//if there are no errors
	alert("Your details are valid!");
	return true;	//submit form
}
