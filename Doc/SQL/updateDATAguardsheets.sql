update guardsheets set state="close" where guardsheets.state = "closed";
update guardsheets set state="blank" where guardsheets.state = "empty";
