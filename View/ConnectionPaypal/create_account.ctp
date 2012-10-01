<h1>Create a PayPal Account</h1>
<?PHP

echo $this->Form->create(false);

echo $this->Form->input('firstName');
echo $this->Form->input('lastName');
echo $this->Form->input('line1', array('label' => 'Address Line 1'));
echo $this->Form->input('line2', array('label' => 'Address Line 2'));
echo $this->Form->input('city');
echo $this->Form->input('state');
echo $this->Form->input('postalCode');
echo $this->Form->input('contactPhoneNumber');
echo $this->Form->input('dateOfBirth', array(
    'type' => 'date',
    'dateFormat' => 'YYYY-MM-DD',
    'minYear' => date('Y') - 100,
    'maxYear' => date('Y') - 1,
    'empty' => true,
));
echo $this->Form->input('emailAddress');

echo $this->Form->end();

?>