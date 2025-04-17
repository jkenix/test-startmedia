<!DOCTYPE html>
<html lang="ru">

<head>
    <meta charset="utf-8" />
    <title>Тестовое задание для Start Media</title>
    <meta
        name="description"
        content="Тестовое задание для Start Media, выполненное Дмитрием." />
    <meta name="author" content="https://github.com/jkenix" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <link
        rel="icon"
        type="image/png"
        href="sources/favicon-96x96.png"
        sizes="96x96" />
    <link rel="icon" type="image/svg+xml" href="sources/favicon.svg" />
    <link rel="shortcut icon" href="sources/favicon.ico" />
    <link
        rel="apple-touch-icon"
        sizes="180x180"
        href="sources/apple-touch-icon.png" />
    <link rel="manifest" href="sources/site.webmanifest.json" />
    <link rel="stylesheet" href="sources/main.css" />
</head>

<body>

    <div class="wrapper">

        <?php
        // Загрузка данных о участниках из JSON файла data_cars.json
        $participants = @json_decode(file_get_contents('sources/data/data_cars.json'), true);
        // Загрузка данных о попытках из JSON файла data_attempts.json
        $attempts_data = @json_decode(file_get_contents('sources/data/data_attempts.json'), true);
        ?>

        <?php
        // Если данные не получены, то ничего не выводится
        if ($participants === null || $attempts_data === null) :
        ?>
            <script>
                console.log("Ошибка загрузки файлов таблицы автогонок!");
            </script>
            <?php
            exit;
            ?>

        <?php else: ?>

            <div class="score-table score-table__content">
                <h1 class="score-table__title">Турнирная таблица автогонок</h1>

                <div class="score-table__sort-status" id="sortStatus"></div>

                <table class="score-table__results" id="score_results">

                    <thead>
                        <tr>
                            <th class="score-table__results-title">Место</th>
                            <th class="score-table__results-title">ФИО</th>
                            <th class="score-table__results-title">Город</th>
                            <th class="score-table__results-title">Машина</th>
                            <th class="score-table__results-title">Попытка 1</th>
                            <th class="score-table__results-title">Попытка 2</th>
                            <th class="score-table__results-title">Попытка 3</th>
                            <th class="score-table__results-title">Попытка 4</th>
                            <th class="score-table__results-title">Итоговые очки</th>
                        </tr>
                    </thead>

                    <tbody>
                        <?php
                        // Подготовка массива для хранения итоговых результатов
                        $score_results = [];
                        foreach ($participants as $participant) {
                            // Получаем уникальный идентификатор участника
                            $id = $participant['id'];
                            // Имя участника
                            $name = $participant['name'];
                            // Город участника
                            $city = $participant['city'];
                            // Машина участника
                            $car = $participant['car'];

                            // Массив для хранения результатов попыток данного участника
                            $scores = [];
                            // Проходим по всем данным попыток чтобы найти результаты текущего участника
                            foreach ($attempts_data as $attempt) {
                                if ($attempt['id'] == $id) {
                                    // Добавляем результат попытки участника в массив
                                    $scores[] = $attempt['result'];
                                }
                            }

                            // Если у участника меньше 4 результатов, дополняем массив нулями
                            for ($i = count($scores); $i < 4; $i++) {
                                $scores[] = 0;
                            }

                            // Суммируем все результаты участника для получения итоговых очков
                            $totalScore = array_sum($scores);

                            // Формируем массив с итоговыми данными участника
                            $score_results[] = [
                                'name' => $name,
                                'city' => $city,
                                'car' => $car,
                                'scores' => $scores,
                                'totalScore' => $totalScore,
                            ];
                        }

                        // Сортируем участников по убыванию итоговых очков
                        usort($score_results, function ($a, $b) {
                            return $b['totalScore'] <=> $a['totalScore'];
                        });

                        // Выводим результаты в виде строк таблицы
                        foreach ($score_results as $index => $result) {
                            echo "<tr>";
                            // Позиция участника в рейтинге
                            echo "<td>" . ($index + 1) . "</td>";
                            // Имя участника
                            echo "<td>{$result['name']}</td>";
                            // Город участника
                            echo "<td>{$result['city']}</td>";
                            // Машина участника
                            echo "<td>{$result['car']}</td>";
                            // Результаты 4 попыток
                            echo "<td>{$result['scores'][0]}</td>";
                            echo "<td>{$result['scores'][1]}</td>";
                            echo "<td>{$result['scores'][2]}</td>";
                            echo "<td>{$result['scores'][3]}</td>";
                            // Итоговое количество очков
                            echo "<td>{$result['totalScore']}</td>";
                            echo "</tr>";
                        }
                        ?>
                    </tbody>

                </table>

            </div>

        <?php endif; ?>

    </div>

    <script src="sources/js/scripts.js"></script>
</body>

</html>