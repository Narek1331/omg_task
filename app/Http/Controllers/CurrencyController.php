<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Log;

class CurrencyController extends Controller
{
    // Метод для получения текущих курсов валют
    public function getLatestRates()
    {
        // Отправляем запрос к внешнему API Центрального Банка России
        $apiUrl = Config::get('currency.api_url');
        $response = Http::get($apiUrl);

        // Проверяем, успешен ли запрос
        if ($response->successful()) {
            // Сериализуем XML-ответ
            $xmlData = simplexml_load_string($response->body());

            // Преобразуем XML в массив JSON
            $jsonData = json_decode(json_encode($xmlData), true);

            // Кешируем данные на 1 час
            Cache::put('latest_rates', $jsonData, 60);

            // Возвращаем данные в формате JSON
            return response()->json($jsonData);
        }

        // В случае ошибки возвращаем сообщение об ошибке
        return response()->json(['error' => 'Failed to fetch currency rates'], 500);
    }

    // Метод для получения исторических курсов валют за определенный период
    public function getHistoricalRates(Request $request)
    {
        // Проверяем, получены ли параметры начальной и конечной даты
        $startDate = $request->query('start_date');
        $endDate = $request->query('end_date');

        // Проверяем, заданы ли обе даты
        if ($startDate && $endDate) {
            // Получаем URL внешнего сервиса из конфигурации
            $apiUrl = Config::get('currency.api_url');

            try {
                // Отправляем запрос к внешнему API для получения данных о курсах валют
                $response = Http::get($apiUrl);

                // Проверяем успешность запроса
                if ($response->successful()) {
                    // Обработка полученных данных и возврат результата

                    // Записываем информацию об успешном запросе в лог
                    Log::info('Successfully retrieved historical currency rates.');

                    return response()->json(['message' => 'Historical rates for the specified period']);
                } else {
                    // Если запрос не удался, записываем информацию об ошибке в лог
                    Log::error('Failed to retrieve historical currency rates.');

                    return response()->json(['error' => 'Failed to fetch historical currency rates'], 500);
                }
            } catch (\Exception $e) {
                // Обработка исключений и запись ошибки в лог
                Log::error('An error occurred while retrieving historical currency rates: ' . $e->getMessage());

                return response()->json(['error' => 'An error occurred while processing your request'], 500);
            }
        }

        // Если не заданы обе даты, возвращаем сообщение об ошибке
        return response()->json(['error' => 'Both start date and end date are required'], 400);
    }
}
