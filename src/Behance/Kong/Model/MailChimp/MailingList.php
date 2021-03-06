<?php namespace Behance\Kong\Model\MailChimp;

use \Behance\Kong\Model;
use \Behance\Kong\Endpoints;
use \Behance\Kong\Exception\MaximumExceededException;

class MailingList extends Model {

  /**
   * The maximum amount of users allowed in a single batch
   * subscribe request.
   *
   * @var int
   */
  const MAX_BATCH_USERS = 10000;

  /**
   * Client string.
   *
   * @var string
   */
  protected $_client_string = 'MailChimp';

  /**
   * Subscribe a user with the provided profile
   * data.
   *
   * http://apidocs.mailchimp.com/api/2.0/lists/subscribe.php
   *
   * @param string $email
   * @param array  $data  associative array of merge vars
   */
  public function subscribe( $email, array $data = [] ) {

    $params = [
        'id'           => $this->__get( 'id' ),
        'email'        => [ 'email' => $email ],
        'double_optin' => false,
        'merge_vars'   => $data,
    ];

    $this->_execute( $params, Endpoints::LIST_SUBSCRIBE, 'POST' );

  } // subscribe

  /**
   * Batch subscribe users to a list.
   *
   * http://apidocs.mailchimp.com/api/2.0/lists/batch-subscribe.php
   *
   * @param array $users See above docs above for array structure
   * @param array options
   *
   */
  public function batchSubscribe( array $users, array $options = [] ) {

    if ( count( $users ) > static::MAX_BATCH_USERS ) {
      throw new MaximumExceededException( 'Can not batch subscribe more than ' . static::MAX_BATCH_USERS . ' at a time.' );
    }

    $params = [
        'id'    => $this->__get( 'id' ),
        'batch' => $users
    ];

    $params = array_merge( $params, $options );

    $this->_execute( $params, Endpoints::LIST_BATCH_SUBSCRIBE, 'POST' );

  } // batchSubscribe

  /**
   * Unsubscribe a user.
   *
   * http://apidocs.mailchimp.com/api/2.0/lists/unsubscribe.php
   *
   * @param string  $email
   * @param boolean $delete
   * @param boolean $send_goodbye
   * @param boolean $notify
   */
  public function unsubscribe( $email, $delete = false, $send_goodbye = false, $notify = false ) {

    $params = [
        'id'            => $this->__get( 'id' ),
        'email'         => [ 'email' => $email ],
        'delete_member' => $delete,
        'send_goodbye'  => $send_goodbye,
        'notify'        => $notify,
    ];

    return $this->_execute( $params, Endpoints::LIST_UNSUBSCRIBE, 'POST' );

  } // unsubscribe

  /**
   * Batch unsubscribe users from a list.
   *
   * http://apidocs.mailchimp.com/api/2.0/lists/batch-unsubscribe.php
   *
   * @param array $users See above docs above for array structure
   * @param boolean $delete
   * @param boolean $send_goodbye
   * @param boolean $notify
   *
   */
  public function batchUnsubscribe( array $users, $delete = false, $send_goodbye = false, $notify = false ) {

    if ( count( $users ) > static::MAX_BATCH_USERS ) {
      throw new MaximumExceededException( 'Can not batch unsubscribe more than ' . static::MAX_BATCH_USERS . ' at a time.' );
    }

    $params = [
        'id'            => $this->__get( 'id' ),
        'batch'         => $users,
        'delete_member' => $delete,
        'send_goodbye'  => $send_goodbye,
        'notify'        => $notify,
    ];

    return $this->_execute( $params, Endpoints::LIST_BATCH_UNSUBSCRIBE, 'POST' );

  } // batchUnsubscribe

} // MailingList
