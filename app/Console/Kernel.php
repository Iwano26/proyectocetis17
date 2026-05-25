protected function schedule(Schedule $schedule)
{
    $schedule->command('asesorias:actualizar-estados')->everyMinute();
    $schedule->command('asesorias:recordatorios')->everyMinute();
    $schedule->command('examenes:recordatorios')->everyMinute();  // ← agrega esto
}