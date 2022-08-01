<?php

namespace Database\Seeders;

use App\Domain\Specialities\Models\Speciality;
use Illuminate\Database\Seeder;

class SpecialtyTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $specialties = [
            'Especialização médica e mercado de trabalho',
            'Residência Médica e Título de Especialista',
            'Especialidades médica, áreas de atuação e formas de acesso',
            'Especialista em Acupuntura',
            'Especialista em Alergia e Imunologia',
            'Especialista em Anestesiologista',
            'Especialista em Angiologia',
            'Especialista em Cardiologia',
            'Especialista em Cirurgia Cardiovascular',
            'Especialista em Cirurgia de Mão',
            'Especialista em Cirurgia de Cabeça e Pescoço',
            'Especialista em Cirurgia do Aparelho Digestivo',
            'Especialista em Cirurgia Geral',
            'Especialista em Cirurgia Oncológica',
            'Especialista em Cirurgia Pediátrica',
            'Especialista em Cirurgia Plástica',
            'Especialista em Cirurgia Torácica',
            'Especialista em Cirurgia Vascular',
            'Especialista em Clínica Médica',
            'Especialista em Coloproctologia',
            'Especialista em Dermatologia',
            'Especialista em Endocrinologia e Metabologia',
            'Especialista em Endoscopia',
            'Especialista em Gastroenterologia',
            'Especialista em Genética Médica',
            'Especialista em Geriatria',
            'Especialista em Ginecologia e Obstetrícia',
            'Especialista em Hematologia e Hemoterapia',
            'Especialista em Homeopatia',
            'Especialista em Infectologia',
            'Especialista em Mastologia',
            'Especialista em Medicina de Emergência',
            'Especialista em Medicina de Família e Comunidade',
            'Especialista em Medicina do Trabalho',
            'Especialista em Medicina de Tráfego',
            'Especialista em Medicina Esportiva',
            'Especialista em Medicina Física e Reabilitação',
            'Especialista em Medicina Intensiva',
            'Especialista em Medicina Legal e Perícia Médica',
            'Especialista em Medicina Nuclear',
            'Especialista em Medicina Preventiva e Social',
            'Especialista em Nefrologia',
            'Especialista em Neurocirurgia',
            'Especialista em Neurologia',
            'Especialista em Nutrologia',
            'Especialista em Oftalmologia',
            'Especialista em Oncologia Clínica',
            'Especialista em Ortopedia e Traumatologia',
            'Especialista em Otorrinolaringologia',
            'Especialista en Patologia',
            'Especialista em Patologia Clínica/Medicina Laboratorial',
            'Especialista em Pediatria',
            'Especialista em Pneumologia',
            'Especialista em Psiquiatria',
            'Especialista em Radiologia e Diagnóstico por Imagem',
            'Especialista em Radioterapia',
            'Especialista em Reumatologia',
            'Especialista em Urologia',
        ];

        foreach ($specialties as $specialty) {
            $data = ['name' => $specialty];

            Speciality::updateOrCreate($data, $data);
        }
    }
}
