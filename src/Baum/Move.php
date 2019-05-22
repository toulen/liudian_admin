<?php
namespace Liudian\Admin\Baum;

use \Illuminate\Events\Dispatcher;

/**
* Move
*/
class Move extends \Baum\Move {

  /**
   * Fire the given move event for the model.
   *
   * @param  string $event
   * @param  bool   $halt
   * @return mixed
   */
  protected function fireMoveEvent($event, $halt = true) {
    if ( !isset(static::$dispatcher) ) return true;

    // Basically the same as \Illuminate\Database\Eloquent\Model->fireModelEvent
    // but we relay the event into the node instance.
    $event = "eloquent.{$event}: ".get_class($this->node);

    $method = $halt ? 'until' : 'fire';

    if(method_exists(static::$dispatcher, $method)) {
        return static::$dispatcher->$method($event, $this->node);
    }

    return true;
  }
}
