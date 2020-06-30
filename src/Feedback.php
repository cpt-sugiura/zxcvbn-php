<?php

namespace ZxcvbnPhp;

use ZxcvbnPhp\Matchers\Match;

/**
 * Feedback - gives some user guidance based on the strength
 * of a password
 *
 * @see zxcvbn/src/feedback.coffee
 */
class Feedback
{
    /**
     * @param int $score
     * @param Match[] $sequence
     * @return array
     */
    public function getFeedback($score, array $sequence)
    {
        // starting feedback
        if (count($sequence) === 0) {
            return [
                'warning' => '',
                'suggestions' => [
                    "いくつかの単語を使用して、一般的なフレーズを避けてください。",
                    "記号、数字、大文字は必要ありません。"
                ]
            ];
        }

        // no feedback if score is good or great.
        if ($score > 2) {
            return [
                'warning' => '',
                'suggestions' => []
            ];
        }

        // tie feedback to the longest match for longer sequences
        $longestMatch = $sequence[0];
        foreach (array_slice($sequence, 1) as $match) {
            if (mb_strlen($match->token) > mb_strlen($longestMatch->token)) {
                $longestMatch = $match;
            }
        }

        $feedback = $longestMatch->getFeedback(count($sequence) === 1);
        $extraFeedback = '別の語を追加した方が良いです。珍しい言葉であればなお良いです。';

        array_unshift($feedback['suggestions'], $extraFeedback);
        return $feedback;
    }
}
