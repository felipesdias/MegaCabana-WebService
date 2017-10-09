<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $this->call(AlergiaSeeder::class);
        $this->call(EstadoSeeder::class);
        $this->call(CidadeSeeder::class);
        $this->call(MedicamentoSeeder::class);
        $this->call(MedicinaAlternativaSeeder::class);
        $this->call(ProfissaoSeeder::class);
        $this->call(VacinaSeeder::class);
        $this->call(ProblemaSaudeSeeder::class);
    }
}
