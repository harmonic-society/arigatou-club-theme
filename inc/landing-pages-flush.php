<?php
/**
 * ランディングページのパーマリンクをフラッシュする一時的なスクリプト
 *
 * このファイルは一度だけ実行され、その後削除できます
 */

// カスタム投稿タイプが登録されているか確認し、パーマリンクをフラッシュ
function arigatou_club_manual_flush_rewrite() {
    // landing_page投稿タイプが存在するかチェック
    if (post_type_exists('landing_page')) {
        flush_rewrite_rules(true);

        // オプションに実行済みフラグを保存
        update_option('arigatou_landing_page_flushed', '1');
    }
}

// オプションをチェックして、まだ実行されていない場合のみ実行
if (get_option('arigatou_landing_page_flushed') !== '1') {
    add_action('init', 'arigatou_club_manual_flush_rewrite', 20);
}