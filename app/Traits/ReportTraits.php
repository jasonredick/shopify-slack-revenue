<?php

namespace App\Traits;

use App\Notifications\ReportRevenue;

trait ReportTraits
{

  protected function processChannel($channel, $parameters = [], $day_string) {
    $store_totals = [];
    foreach ($channel->stores as $store) {
      $revenue = 0;
      $request_url = "https://{$store->api_key}:{$store->api_password}@{$store->handle}.myshopify.com/admin/orders.json";
      $revenue = $this->getOrderRevenue($revenue, $request_url, $parameters);
      $store_totals[$store->name] = $revenue;
    }

    if (count($store_totals) === 0) {
      return;
    }

    $channel->setSlackChannel($channel->web_hook);
    $channel->notify(new ReportRevenue("*{$day_string} Revenue:*"));
    foreach ($store_totals as $store => $total) {
      $channel->notify(new ReportRevenue("{$store}: \${$total}"));
    }
  }

  protected function getOrderRevenue($revenue = 0, $url, $parameters = [], $page = 1) {
    $parameters['page'] = $page;
    $request_url = $url . '?' . http_build_query($parameters);
    $response = $this->client->get($request_url);
    $json = json_decode($response->getBody());
    foreach ($json->orders as $order) {
      $revenue += (float) $order->total_price;
    }

    if (count($json->orders) === 250) {
      $page++;
      $revenue = $this->getOrderRevenue($revenue, $url, $parameters, $page);
    }

    return $revenue;
  }

}