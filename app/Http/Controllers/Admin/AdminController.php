<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;
use Carbon\Carbon;

class AdminController extends Controller
{
    public function index(Request $request)
    {
        // Lấy giá trị của các bộ lọc
        $dateFilter = $request->input('date_filter', '7_days');
        $yearFilter = $request->input('year_filter', Carbon::now()->year);  // Mặc định là năm hiện tại
        $monthFilter = $request->input('month_filter', null); // Tháng lọc nếu có

        // Lọc theo trạng thái đơn hàng
        $statuses = [
            'pending' => $this->getOrdersByStatus('Pending', $dateFilter),
            'Processed' => $this->getOrdersByStatus('Processed', $dateFilter),
            'In Transit' => $this->getOrdersByStatus('In Transit', $dateFilter),
            'Delivered' => $this->getOrdersByStatus('Delivered', $dateFilter),
        ];

        // Thống kê doanh thu theo tháng hoặc năm
        if ($monthFilter) {
            $revenueData = $this->getRevenueDataByMonth($monthFilter, $yearFilter); // Doanh thu theo tháng và năm
        } else {
            $revenueData = $this->getRevenueDataByYear($yearFilter); // Doanh thu theo năm
        }

        // Trả về view cùng với các dữ liệu đã lọc
        return view('admin.dashboard', compact('statuses', 'dateFilter', 'revenueData', 'monthFilter', 'yearFilter'));
    }

    // Lọc đơn hàng theo trạng thái và mốc thời gian
    private function getOrdersByStatus($status, $dateFilter)
    {
        // Tạo query với trạng thái đơn hàng
        $query = Order::where('status', $status);

        // Lọc theo mốc thời gian
        if ($dateFilter === '1_day') {
            $query->whereDate('created_at', Carbon::today());  // Lọc đơn hàng trong ngày hôm nay
        } elseif ($dateFilter === '3_days') {
            $query->whereDate('created_at', '>=', Carbon::today()->subDays(3));
        } elseif ($dateFilter === '5_days') {
            $query->whereDate('created_at', '>=', Carbon::today()->subDays(5));
        } elseif ($dateFilter === '7_days') {
            $query->whereDate('created_at', '>=', Carbon::today()->subDays(7));
        }

        return $query->count();
    }

    // Lọc doanh thu theo ngày (7 ngày mặc định)
    private function getRevenueData($dateFilter)
    {
        $query = Order::selectRaw('DATE(created_at) as date, SUM(total_amount) as total_revenue')
            ->where('status', 'Delivered')
            ->where('created_at', '>=', Carbon::now()->subDays($dateFilter === '7_days' ? 7 : (int)str_replace('_days', '', $dateFilter)))
            ->groupByRaw('DATE(created_at)')
            ->orderBy('date', 'asc')
            ->get();

        // Format lại dữ liệu doanh thu theo từng ngày
        return $this->formatRevenueData($query);
    }

    // Lọc doanh thu theo tháng trong năm (khi chưa chọn tháng)
    private function getRevenueDataByYear($year)
    {
        // Lấy doanh thu theo tháng trong năm
        $monthlyRevenueData = Order::selectRaw('MONTH(created_at) as month, SUM(total_amount) as total_revenue')
            ->where('status', 'Delivered')
            ->whereYear('created_at', $year)
            ->groupByRaw('MONTH(created_at)')
            ->orderBy('month', 'asc')
            ->get();

        // Chuẩn hóa lại dữ liệu theo từng tháng trong năm
        $formattedMonthlyRevenue = [];

        for ($i = 1; $i <= 12; $i++) {
            $monthData = $monthlyRevenueData->firstWhere('month', $i);
            $formattedMonthlyRevenue[] = [
                'month' => Carbon::create()->month($i)->format('F'), // Tên tháng (January, February, ...)
                'revenue' => $monthData ? $monthData->total_revenue : 0,
            ];
        }

        return $formattedMonthlyRevenue;
    }

    // Lọc doanh thu theo tuần trong tháng (khi đã chọn tháng)
    private function getRevenueDataByMonth($month, $year)
    {
        // Lấy ngày đầu và ngày cuối của tháng
        $startDate = Carbon::create($year, $month, 1);
        $endDate = $startDate->copy()->endOfMonth(); // Ngày cuối cùng của tháng

        // Lấy doanh thu theo ngày trong tháng đã chọn
        $dailyRevenueData = Order::selectRaw('DAY(created_at) as day, SUM(total_amount) as total_revenue')
            ->where('status', 'Delivered')
            ->whereMonth('created_at', $month)
            ->whereYear('created_at', $year)
            ->whereBetween('created_at', [$startDate, $endDate])
            ->groupByRaw('DAY(created_at)')
            ->orderBy('day', 'asc')
            ->get();

        // Khởi tạo mảng lưu trữ doanh thu theo tuần
        $weeklyRevenueData = [];
        $currentWeekRevenue = 0;
        $currentWeek = 1;

        // Chia các ngày thành các tuần theo yêu cầu
        foreach ($dailyRevenueData as $data) {
            // Xác định tuần của ngày này
            if ($data->day >= 1 && $data->day <= 7) {
                $week = 1;
            } elseif ($data->day >= 8 && $data->day <= 14) {
                $week = 2;
            } elseif ($data->day >= 15 && $data->day <= 21) {
                $week = 3;
            } elseif ($data->day >= 22 && $data->day <= 28) {
                $week = 4;
            } else {
                $week = 5;
            }

            // Nếu là tuần mới, lưu tuần cũ và bắt đầu tính tuần mới
            if ($week !== $currentWeek) {
                // Lưu doanh thu của tuần cũ
                $weeklyRevenueData[] = [
                    'week' => 'Week ' . $currentWeek,
                    'revenue' => $currentWeekRevenue,
                ];

                // Chuyển sang tuần mới
                $currentWeek = $week;
                $currentWeekRevenue = 0; // Đặt lại doanh thu của tuần mới
            }

            // Cộng doanh thu vào tuần hiện tại
            $currentWeekRevenue += $data->total_revenue;
        }

        // Lưu tuần cuối (dù có thiếu ngày)
        if ($currentWeekRevenue > 0) {
            $weeklyRevenueData[] = [
                'week' => 'Week ' . $currentWeek,
                'revenue' => $currentWeekRevenue,
            ];
        }

        return $weeklyRevenueData;
    }

    // Hàm format doanh thu
    private function formatRevenueData($revenueData)
    {
        $formattedRevenue = [];

        foreach ($revenueData as $data) {
            $formattedRevenue[] = [
                'date' => $data->date ?? $data->day, // Dựa trên ngày
                'revenue' => $data->total_revenue,
            ];
        }

        return $formattedRevenue;
    }
}
