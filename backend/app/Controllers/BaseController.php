<?php

namespace App\Controllers;

use CodeIgniter\Controller;
use CodeIgniter\HTTP\CLIRequest;
use CodeIgniter\HTTP\IncomingRequest;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use Psr\Log\LoggerInterface;
use App\Models\CartItemModel;

/**
 * Class BaseController
 *
 * BaseController provides a convenient place for loading components
 * and performing functions that are needed by all your controllers.
 * Extend this class in any new controllers:
 *     class Home extends BaseController
 *
 * For security be sure to declare any new methods as protected or private.
 */
abstract class BaseController extends Controller
{
    protected $request;
    protected $helpers = [];

    public function initController(RequestInterface $request, ResponseInterface $response, LoggerInterface $logger)
    {
        parent::initController($request, $response, $logger);

        // ── Global cart total for header badge ──
        $this->shareCartTotal();
    }

    private function shareCartTotal(): void
    {
        $user   = session()->get('user');
        $userId = $user['id'] ?? null;

        $cond = $userId
            ? ['user_id' => $userId]
            : ['session_id' => session()->get('cart_session_id') ?? ''];

        $model  = new CartItemModel();
        $result = $model->selectSum('quantity')->where($cond)->first();

        service('renderer')->setData([
            'cartTotal' => (int)($result['quantity'] ?? 0),
        ]);
    }
}
