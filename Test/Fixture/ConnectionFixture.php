<?php
/**
 * ConnectionFixture
 *
 */
class ConnectionFixture extends CakeTestFixture {

/**
 * Fields
 *
 * @var array
 */
	public $fields = array(
		'id' => array('type' => 'string', 'null' => false, 'default' => NULL, 'length' => 36, 'key' => 'primary', 'collate' => 'utf8_general_ci', 'charset' => 'utf8'),
		'user_id' => array('type' => 'integer', 'null' => false, 'default' => NULL),
		'type' => array('type' => 'string', 'null' => false, 'default' => NULL, 'length' => 100, 'collate' => 'utf8_general_ci', 'comment' => 'facebook, twitter, etc.', 'charset' => 'utf8'),
		'value' => array('type' => 'text', 'null' => false, 'default' => NULL, 'collate' => 'utf8_general_ci', 'comment' => 'serialized array of authentication responses', 'charset' => 'utf8'),
		'creator_id' => array('type' => 'integer', 'null' => false, 'default' => NULL),
		'modifier_id' => array('type' => 'integer', 'null' => false, 'default' => NULL),
		'created' => array('type' => 'datetime', 'null' => false, 'default' => NULL),
		'modified' => array('type' => 'datetime', 'null' => false, 'default' => NULL),
		'indexes' => array('PRIMARY' => array('column' => 'id', 'unique' => 1)),
		'tableParameters' => array('charset' => 'utf8', 'collate' => 'utf8_general_ci', 'engine' => 'MyISAM')
	);

/**
 * Records
 *
 * @var array
 */
	public $records = array(
		array(
			'id' => '509be0c0-b40c-4d65-b718-189c00000000',
			'user_id' => 1,
			'type' => 'Lorem ipsum dolor sit amet',
			'value' => 'Lorem ipsum dolor sit amet, aliquet feugiat. Convallis morbi fringilla gravida, phasellus feugiat dapibus velit nunc, pulvinar eget sollicitudin venenatis cum nullam, vivamus ut a sed, mollitia lectus. Nulla vestibulum massa neque ut et, id hendrerit sit, feugiat in taciti enim proin nibh, tempor dignissim, rhoncus duis vestibulum nunc mattis convallis.',
			'creator_id' => 1,
			'modifier_id' => 1,
			'created' => '2012-11-08 16:41:36',
			'modified' => '2012-11-08 16:41:36'
		),
		array(
			'id' => '509be0c0-2f18-456b-b0d8-189c00000000',
			'user_id' => 2,
			'type' => 'Lorem ipsum dolor sit amet',
			'value' => 'Lorem ipsum dolor sit amet, aliquet feugiat. Convallis morbi fringilla gravida, phasellus feugiat dapibus velit nunc, pulvinar eget sollicitudin venenatis cum nullam, vivamus ut a sed, mollitia lectus. Nulla vestibulum massa neque ut et, id hendrerit sit, feugiat in taciti enim proin nibh, tempor dignissim, rhoncus duis vestibulum nunc mattis convallis.',
			'creator_id' => 2,
			'modifier_id' => 2,
			'created' => '2012-11-08 16:41:36',
			'modified' => '2012-11-08 16:41:36'
		),
		array(
			'id' => '509be0c0-7180-47c9-a76f-189c00000000',
			'user_id' => 3,
			'type' => 'Lorem ipsum dolor sit amet',
			'value' => 'Lorem ipsum dolor sit amet, aliquet feugiat. Convallis morbi fringilla gravida, phasellus feugiat dapibus velit nunc, pulvinar eget sollicitudin venenatis cum nullam, vivamus ut a sed, mollitia lectus. Nulla vestibulum massa neque ut et, id hendrerit sit, feugiat in taciti enim proin nibh, tempor dignissim, rhoncus duis vestibulum nunc mattis convallis.',
			'creator_id' => 3,
			'modifier_id' => 3,
			'created' => '2012-11-08 16:41:36',
			'modified' => '2012-11-08 16:41:36'
		),
		array(
			'id' => '509be0c0-b258-4839-badd-189c00000000',
			'user_id' => 4,
			'type' => 'Lorem ipsum dolor sit amet',
			'value' => 'Lorem ipsum dolor sit amet, aliquet feugiat. Convallis morbi fringilla gravida, phasellus feugiat dapibus velit nunc, pulvinar eget sollicitudin venenatis cum nullam, vivamus ut a sed, mollitia lectus. Nulla vestibulum massa neque ut et, id hendrerit sit, feugiat in taciti enim proin nibh, tempor dignissim, rhoncus duis vestibulum nunc mattis convallis.',
			'creator_id' => 4,
			'modifier_id' => 4,
			'created' => '2012-11-08 16:41:36',
			'modified' => '2012-11-08 16:41:36'
		),
		array(
			'id' => '509be0c0-f330-4a13-bcbd-189c00000000',
			'user_id' => 5,
			'type' => 'Lorem ipsum dolor sit amet',
			'value' => 'Lorem ipsum dolor sit amet, aliquet feugiat. Convallis morbi fringilla gravida, phasellus feugiat dapibus velit nunc, pulvinar eget sollicitudin venenatis cum nullam, vivamus ut a sed, mollitia lectus. Nulla vestibulum massa neque ut et, id hendrerit sit, feugiat in taciti enim proin nibh, tempor dignissim, rhoncus duis vestibulum nunc mattis convallis.',
			'creator_id' => 5,
			'modifier_id' => 5,
			'created' => '2012-11-08 16:41:36',
			'modified' => '2012-11-08 16:41:36'
		),
		array(
			'id' => '509be0c0-346c-4232-92c9-189c00000000',
			'user_id' => 6,
			'type' => 'Lorem ipsum dolor sit amet',
			'value' => 'Lorem ipsum dolor sit amet, aliquet feugiat. Convallis morbi fringilla gravida, phasellus feugiat dapibus velit nunc, pulvinar eget sollicitudin venenatis cum nullam, vivamus ut a sed, mollitia lectus. Nulla vestibulum massa neque ut et, id hendrerit sit, feugiat in taciti enim proin nibh, tempor dignissim, rhoncus duis vestibulum nunc mattis convallis.',
			'creator_id' => 6,
			'modifier_id' => 6,
			'created' => '2012-11-08 16:41:36',
			'modified' => '2012-11-08 16:41:36'
		),
		array(
			'id' => '509be0c0-7b20-4fb7-8c0a-189c00000000',
			'user_id' => 7,
			'type' => 'Lorem ipsum dolor sit amet',
			'value' => 'Lorem ipsum dolor sit amet, aliquet feugiat. Convallis morbi fringilla gravida, phasellus feugiat dapibus velit nunc, pulvinar eget sollicitudin venenatis cum nullam, vivamus ut a sed, mollitia lectus. Nulla vestibulum massa neque ut et, id hendrerit sit, feugiat in taciti enim proin nibh, tempor dignissim, rhoncus duis vestibulum nunc mattis convallis.',
			'creator_id' => 7,
			'modifier_id' => 7,
			'created' => '2012-11-08 16:41:36',
			'modified' => '2012-11-08 16:41:36'
		),
		array(
			'id' => '509be0c0-bbf8-47b5-a431-189c00000000',
			'user_id' => 8,
			'type' => 'Lorem ipsum dolor sit amet',
			'value' => 'Lorem ipsum dolor sit amet, aliquet feugiat. Convallis morbi fringilla gravida, phasellus feugiat dapibus velit nunc, pulvinar eget sollicitudin venenatis cum nullam, vivamus ut a sed, mollitia lectus. Nulla vestibulum massa neque ut et, id hendrerit sit, feugiat in taciti enim proin nibh, tempor dignissim, rhoncus duis vestibulum nunc mattis convallis.',
			'creator_id' => 8,
			'modifier_id' => 8,
			'created' => '2012-11-08 16:41:36',
			'modified' => '2012-11-08 16:41:36'
		),
		array(
			'id' => '509be0c0-fcd0-4c72-b5ec-189c00000000',
			'user_id' => 9,
			'type' => 'Lorem ipsum dolor sit amet',
			'value' => 'Lorem ipsum dolor sit amet, aliquet feugiat. Convallis morbi fringilla gravida, phasellus feugiat dapibus velit nunc, pulvinar eget sollicitudin venenatis cum nullam, vivamus ut a sed, mollitia lectus. Nulla vestibulum massa neque ut et, id hendrerit sit, feugiat in taciti enim proin nibh, tempor dignissim, rhoncus duis vestibulum nunc mattis convallis.',
			'creator_id' => 9,
			'modifier_id' => 9,
			'created' => '2012-11-08 16:41:36',
			'modified' => '2012-11-08 16:41:36'
		),
		array(
			'id' => '509be0c0-3e0c-4c8a-94c2-189c00000000',
			'user_id' => 10,
			'type' => 'Lorem ipsum dolor sit amet',
			'value' => 'Lorem ipsum dolor sit amet, aliquet feugiat. Convallis morbi fringilla gravida, phasellus feugiat dapibus velit nunc, pulvinar eget sollicitudin venenatis cum nullam, vivamus ut a sed, mollitia lectus. Nulla vestibulum massa neque ut et, id hendrerit sit, feugiat in taciti enim proin nibh, tempor dignissim, rhoncus duis vestibulum nunc mattis convallis.',
			'creator_id' => 10,
			'modifier_id' => 10,
			'created' => '2012-11-08 16:41:36',
			'modified' => '2012-11-08 16:41:36'
		),
	);
}
