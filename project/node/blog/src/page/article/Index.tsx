import React from "react";
import { Data, State } from "./index.type";
import { Table, Modal, Button } from "antd";

import { Link } from "react-router-dom";

export default class Article extends React.Component {
  public state: State = {
    visible: false,
  };

  constructor(props: {}) {
    super(props);
    this.modalShow = this.modalShow.bind(this);
    this.modalHide = this.modalHide.bind(this);
  }

  modalShow() {
    this.setState({ visible: true });
  }

  modalHide() {
    this.setState({ visible: false });
  }

  columns: Array<{}> = [
    {
      title: "编号",
      dataIndex: "name",
      render: (text: string) => <a>{text}</a>,
    },
    {
      title: "标题",
      className: "column-money",
      dataIndex: "money",
      align: "right",
    },
    {
      title: "Address",
      dataIndex: "address",
      render: (text: string) => {
        return (
          <Button.Group>
            <Link to="id">
              <Button>编辑</Button>
            </Link>
          </Button.Group>
        );
      },
    },
  ];

  data: Array<Data> = [
    {
      key: "1",
      name: "John Brown",
      money: "￥300,000.00",
      address: "New York No. 1 Lake Park",
    },
    {
      key: "2",
      name: "Jim Green",
      money: "￥1,256,000.00",
      address: "London No. 1 Lake Park",
    },
    {
      key: "3",
      name: "Joe Black",
      money: "￥120,000.00",
      address: "Sidney No. 1 Lake Park",
    },
  ];

  render() {
    return (
      <>
        <Table columns={this.columns} dataSource={this.data} bordered />
      </>
    );
  }
}
