#include <boost/asio.hpp>
#include <boost/array.hpp>
#include <iostream>
#include <string>
#include <thread>

using boost::asio::ip::tcp;

int count_connections = 0;


void handler(
	const boost::system::error_code& error, // Result of operation.
	std::size_t bytes_transferred           // Number of bytes read.
) {

};

void broadcast(tcp::socket* socket[], std::string buffer) {
	int c = 0;
	for (;;) {
		if (count_connections) {
			if (!buffer.empty()) {
				buffer.clear();
			}
			socket[c]->async_read_some(boost::asio::buffer(buffer),	handler);
			if (!buffer.empty()) {
				for (int i = 0; i < count_connections; i++) {
					socket[i]->write_some(boost::asio::buffer(buffer));
				}
			}
			c++;
			if (c >= count_connections) {
				c = 0;
			}
		}
	}
}


void read_mes(tcp::socket* socket, std::string buffer) {
	for (;;)
	{
		socket->read_some(boost::asio::buffer(buffer));
		if (!buffer.empty()) {
			std::cout << buffer << std::endl;
		}
	}
}

int main(int argc, char* argv[])
{
	setlocale(0, "");

	if (argc < 2) {
		std::cout << "Недостаточно аргументов командной строки\n";
	}

	if (argv[1][0] == 'c') {
		try
		{
			boost::asio::io_context io;
			tcp::resolver resolver(io);
			tcp::resolver::results_type endpoints = resolver.resolve("127.0.0.1", "80");

			tcp::socket* socket = new tcp::socket(io);
			boost::asio::connect(*socket, endpoints);
			std::cout << "Подключено\n";
			std::string buffer;
			buffer.resize(4096);
			

			std::thread reader(read_mes,socket,buffer);
			reader.detach();

			for (;;)
			{
				std::cin >> buffer;
				boost::system::error_code error;

				socket->write_some(boost::asio::buffer(buffer));

				if (error == boost::asio::error::eof)
					continue;
				if (error)
					throw boost::system::system_error(error);
			}
		}
		catch (std::exception& e)
		{
			std::cerr << e.what() << std::endl;
		}
	}
	else if (argv[1][0] == 's') {
		try
		{
			boost::asio::io_context io_context;
			tcp::acceptor acceptor(io_context, tcp::endpoint(tcp::v4(), 80));
			std::string buffer;
			buffer.resize(4096);

			tcp::socket* sockets[30];
			bool flag = false;
			std::thread stream(broadcast, sockets, buffer);

			for (;;)
			{
				tcp::socket* socket = new tcp::socket(io_context);
				std::cout << "Ожидание подключения\n";
				acceptor.accept(*socket);
				std::cout << "Клиент подключен\n";
				sockets[count_connections] = socket;
				count_connections++;
				if (!flag) {
					stream.detach();
					flag = true;
				}
				boost::system::error_code ignored_error;
			}
		}
		catch (std::exception& e)
		{
			std::cerr << e.what() << std::endl;
		}
	}

	 
	return 0;
}