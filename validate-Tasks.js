
function validateTask() {
	
	var taskName = document.forms[0].name; //Access input for task name
	var description = document.forms[0].description; //Access input for task description
	var startDate = document.forms[0].startDate; //Access input for task start date
	var deadline = document.forms[0].deadline; //Access input for task deadline
	
	
	var now = new Date(); //Today's date
	var today = new Date(now.getFullYear(), now.getMonth(), now.getDate());	//Today's date calculated from midnight
	var sDate = new Date(startDate.value); //Task start date
	var dDay = new Date(deadline.value);	//Task deadline
	
	var pattern = /^[A-Za-z\s.]+$/; //pattern for task description and task name
	var errorMessage = "There area few errors with the form inputs: \n"; //Error message to be displayed
	var errorNum = 0;	//Number of errors found, also used to index error message
	
	
	//validate input for task name
	if (taskName.value == ""){
	errorNum++;
	errorMessage += errorNum + ". Please provide a name for the task! \n";
	taskName.focus();
	}
	
	//checking for pattern match in task
	if (!taskName.value.match(pattern)) {
		errorNum++;
	errorMessage += errorNum + ". Please enter a valid input for task name! \n";
		taskName.focus();
	}
	
	//validate task description
	if (description.value == "") {
		errorNum++;
	errorMessage += errorNum + ". Please provide a description for the task! \n";
		description.focus();
	}
	
	//checking for pattern match in task description
	if (!description.value.match(pattern)) {
		errorNum++;
	errorMessage += errorNum + ". Please enter a valid input for task description! \n";
		taskName.focus();
	}
	
	//validate start date for task
	// Compare dates by comparing the millisecond representations. 
	if (startDate.value == "") {
		errorNum++;
	errorMessage += errorNum + ". Please enter a valid start date for the task! \n";
		deadline.focus();
	}else if (sDate.getTime() < today.getTime()) {
		errorNum++;
	errorMessage += errorNum + ". Start date cannot be later than today! \n";
		startDate.focus();
	} else if (sDate.getTime() > dDay.getTime()) {
		errorNum++;
	errorMessage += errorNum + ". Start date cannot be after deadline! \n";
		startDate.focus(); 
	}
	
	//validate deadline date for task
	// Compare dates by comparing the millisecond representations
	if (deadline.value == "") {
		errorNum++;
	errorMessage += errorNum + ". Please enter a valid deadline for the task! \n";
		deadline.focus();
	}
	else if (dDay.getTime() < today.getTime()) {
		errorNum++;
	errorMessage += errorNum + ". Task deadline cannot be earlier than today! \n";
		deadline.focus();
	} else if (dDay.getTime() < sDate.getTime()) {
		errorNum++;
	errorMessage += errorNum + ". Task dealine cannot be earlier than start date! \n";
		deadline.focus();
	} else if (dDay.getTime() == today.getTime()) {
		errorNum++;
	errorMessage += errorNum + ". Task deadline cannot be today! \n";
		deadline.focus();
	}
	
	
	//if the number of errors is greater than 0, an alert containing error message will be displayed
	if (errorNum > 0) {
		alert(errorMessage);
		return false;
	}
	
	//if there are no errors
	alert("Task details are valid!");
	return true;	//submit form
}