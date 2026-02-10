<?php

namespace Tests\Feature;

use App\Models\TiketKerusakan;
use App\Models\TiketKerusakanLog;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class TicketUpgradeTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_update_priority_assignee_target_and_log_ticket_changes(): void
    {
        Carbon::setTestNow(Carbon::create(2026, 2, 10, 16, 0, 0, 'Asia/Jakarta'));

        $admin = User::create([
            'nip' => '198001012006041001',
            'nama' => 'Admin Utama',
            'password' => Hash::make('password123'),
            'role' => 'admin',
        ]);

        $assignee = User::create([
            'nip' => '198001012006041002',
            'nama' => 'Admin Teknisi',
            'password' => Hash::make('password123'),
            'role' => 'admin',
        ]);

        $ticket = TiketKerusakan::create([
            'nomor_bmn' => '3100102001-8',
            'pelapor' => 'Budi Santoso',
            'jenis_kerusakan' => 'berat',
            'deskripsi' => 'Monitor tidak menyala',
            'status' => 'open',
        ]);

        $response = $this->actingAs($admin)->put(route('admin.tiket.update', $ticket->id), [
            'status' => 'diproses',
            'priority' => 'high',
            'assigned_to' => $assignee->id,
            'target_selesai_at' => '2026-02-15 12:00:00',
            'admin_notes' => 'Perlu penggantian komponen daya.',
        ]);

        $response->assertRedirect();

        $ticket->refresh();
        $this->assertSame('diproses', $ticket->status);
        $this->assertSame('high', $ticket->priority);
        $this->assertSame($assignee->id, $ticket->assigned_to);
        $this->assertNotNull($ticket->target_selesai_at);
        $this->assertSame('Perlu penggantian komponen daya.', $ticket->admin_notes);
        $this->assertNull($ticket->closed_at);

        $log = TiketKerusakanLog::where('ticket_id', $ticket->id)->latest('id')->first();
        $this->assertNotNull($log);
        $this->assertSame($admin->id, $log->user_id);
        $this->assertSame('open', $log->from_status);
        $this->assertSame('diproses', $log->to_status);
        $this->assertSame('Perlu penggantian komponen daya.', $log->note);
    }
}
