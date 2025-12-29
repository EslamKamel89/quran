<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class QuranController extends Controller {

    public function suraIndex() {
        $suras = DB::table('sura')->get();

        return response()->json([
            'status' => 200,
            'data' => $suras
        ]);
    }
    public function getSuraById($id) {
        try {
            $sura = DB::Table('sura')
                ->where('id', $id)
                ->select('sura_ar')
                ->first();
            if (!$sura) {
                return response()->json([
                    'status' => 400,
                    'message' => 'sura not found'
                ], 400);
            }
            return response()->json([
                'status' => 200,
                'data' => $sura,
            ]);
        } catch (\Throwable $e) {
            return response()->json([
                'status' => 500,
                'message' => $e->getMessage()
            ]);
        }
    }

    public function getFull($page) {
        try {
            $data = DB::select("
                SELECT
                    words.id AS word_id,

                    MAX(words.position) AS position,
                    MAX(words.char_type) AS char_type,
                    MAX(words.line) AS line,
                    MAX(words.ayat_id) AS ayaNo,
                    MAX(words.juz) AS juz,
                    MAX(words.sura_id) AS sura_id,
                    MAX(words.code) AS word_ar,
                    MAX(words.page) AS page,

                    MAX(ayat.id) AS ayaId,

                    MAX(tag_word.id) AS has_tag,
                    MAX(tags.id) AS tag_id,

                    MAX(videos.url) AS word_video_url,
                    MAX(ayatvideos.url) AS aya_video_url

                FROM words

                LEFT JOIN videos
                    ON videos.word_id = words.id
                    AND videos.type = 'tag'
                    AND videos.enabled = 1

                JOIN ayat
                    ON words.sura_id = ayat.sura_id
                    AND words.ayat_id = ayat.ayah

                LEFT JOIN videos ayatvideos
                    ON ayatvideos.ayat_id = ayat.id
                    AND ayatvideos.type = 'explain'
                    AND ayatvideos.enabled = 1

                LEFT JOIN tag_word
                    ON tag_word.word_id = words.id
                    AND tag_word.enabled = 1

                LEFT JOIN tags
                    ON tags.id = tag_word.tag_id
                    AND tags.enabled = 1

                WHERE words.page = ?
                GROUP BY words.id
                ORDER BY words.id
", [$page]);


            return response()->json([
                'status' => 200,
                'data' => $data
            ]);
        } catch (\Throwable $e) {
            return response()->json([
                'status' => 500,
                'message' => $e->getMessage()
            ]);
        }
    }

    public function getSuraByPage($suraId) {
        try {
            $sura = DB::table('sura')
                ->where('id', $suraId)
                ->select('sura_ar')
                ->first();

            if (!$sura) {
                return response()->json([
                    'status' => 400,
                    'message' => 'Sura not found'
                ]);
            }

            return response()->json([
                'status' => 200,
                'data' => $sura
            ]);
        } catch (\Throwable $e) {
            return response()->json([
                'status' => 500,
                'message' => $e->getMessage()
            ]);
        }
    }

    public function pageAyatSura($page) {
        try {
            $data = DB::table('ayat')
                ->where('page', $page)
                ->select('ayah', 'sura_id')
                ->get();

            return response()->json([
                'status' => 200,
                'data' => $data
            ]);
        } catch (\Throwable $e) {
            return response()->json([
                'status' => 500,
                'message' => $e->getMessage()
            ]);
        }
    }

    public function getJuz($sura, $aya) {
        try {
            $row = DB::table('ayat')
                ->where('sura_id', $sura)
                ->where('ayah', $aya)
                ->select('juz')
                ->limit(1)
                ->first();

            if (!$row) {
                return response()->json([
                    'status' => 400,
                    'message' => 'Aya not found'
                ]);
            }

            return response()->json([
                'status' => 200,
                'data' => [
                    'juz' => $row->juz
                ]
            ]);
        } catch (\Throwable $e) {
            return response()->json([
                'status' => 500,
                'message' => $e->getMessage()
            ]);
        }
    }

    public function searchByWord($key) {
        try {
            $data = DB::table('ayat')
                ->join('sura', 'sura.id', '=', 'ayat.sura_id')
                ->where('ayat.simple', 'LIKE', "%{$key}%")
                ->selectRaw('
                COUNT(*) as count,
                sura.id as sura_id,
                sura.sura_ar,
                sura.sura_en
            ')
                ->groupBy('sura.id', 'sura.sura_ar', 'sura.sura_en')
                ->get();

            return response()->json([
                'status' => 200,
                'data' => $data
            ]);
        } catch (\Throwable $e) {
            return response()->json([
                'status' => 500,
                'message' => $e->getMessage()
            ]);
        }
    }
}
