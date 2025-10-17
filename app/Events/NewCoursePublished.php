<?php

namespace App\Events;
use App\Models\Course; 
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
class NewCoursePublished implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $course;

    public function __construct(Course $course)
    {
        $this->course = $course;
    }

    public function broadcastOn()
    {
        return new Channel('public.courses');
    }

    public function broadcastWith()
    {
        return [
            'flash' => [
                'message' => 'Nouveau cours disponible: ' . $this->course->name,
                'course' => [
                    'id' => $this->course->id,
                    'name' => $this->course->name,
                    // autres données si besoin
                ]
            ]
        ];
    }
    public function broadcastAs()
{
    return 'NewCoursePublished';
}

}